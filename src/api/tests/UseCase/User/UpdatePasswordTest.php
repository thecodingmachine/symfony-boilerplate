<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\ResetPasswordTokenDao;
use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\ResetPasswordToken;
use App\Domain\Model\User;
use App\Domain\Throwable\InvalidModel;
use App\Tests\UseCase\DummyValues;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\UpdatePassword;
use DateInterval;
use Safe\DateTimeImmutable;
use TheCodingMachine\TDBM\TDBMException;

use function password_verify;
use function PHPUnit\Framework\assertTrue;

class UpdatePasswordTest extends UseCaseTestCase
{
    private ResetPasswordTokenDao $resetPasswordTokenDao;
    private UpdatePassword $updatePassword;

    protected function setUp(): void
    {
        parent::setUp();
        $userDao                     = self::getFromContainer(UserDao::class);
        $this->resetPasswordTokenDao = self::getFromContainer(ResetPasswordTokenDao::class);
        $this->updatePassword        = self::getFromContainer(UpdatePassword::class);

        $user = new User(
            'foo',
            'bar',
            'foo.bar@foo.com',
            Locale::EN(),
            Role::ADMINISTRATOR()
        );
        $userDao->save($user);

        $validUntil = new DateTimeImmutable();
        $validUntil = $validUntil->add(new DateInterval('P1D')); // Add one day to current date time.

        $resetPasswordToken = new ResetPasswordToken(
            $user,
            'foo',
            $validUntil
        );
        $resetPasswordToken->setId('1');
        $this->resetPasswordTokenDao->save($resetPasswordToken);
    }

    /**
     * @group        User
     */
    public function testUpdatesThePasswordAndDeletesTheToken(): void
    {
        $resetPasswordToken = $this->resetPasswordTokenDao->getById('1');
        $user               = $resetPasswordToken->getUser();

        $this->updatePassword->updatePassword(
            $resetPasswordToken,
            'foo',
            'foobarfoo',
            'foobarfoo'
        );

        $this->expectException(TDBMException::class);
        assertTrue(password_verify('foobarfoo', $user->getPassword()));
        $this->resetPasswordTokenDao->getById($resetPasswordToken->getId());
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public function providesInvalidPasswords(): iterable
    {
        yield 'Blank password' => [DummyValues::BLANK, DummyValues::BLANK];
        yield 'Password < 8' => ['foo', 'foo'];
        yield 'Wrong password confirmation' => ['foobarfoo', 'barfoobar'];

        //        // We do not test "@Assert\NotCompromisedPassword"
        //        // as it is disable when "APP_ENV = test".
        //        // See config/packages/test/validator.yaml.
    }

    /**
     * @dataProvider providesInvalidPasswords
     * @group        User
     */
    public function testThrowsAnExceptionIfInvalidPassword(string $newPassword, string $passwordConfirmation): void
    {
        $resetPasswordToken = $this->resetPasswordTokenDao->getById('1');
        $this->expectException(InvalidModel::class);
        $this->updatePassword->updatePassword(
            $resetPasswordToken,
            'foo',
            $newPassword,
            $passwordConfirmation
        );
    }
}
