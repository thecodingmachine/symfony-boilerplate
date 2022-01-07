<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\ResetPasswordTokenDao;
use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\ResetPasswordToken;
use App\Domain\Model\User;
use App\Tests\UseCase\AsyncTransport;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\ResetPassword\ResetPassword;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use TheCodingMachine\TDBM\TDBMException;

use function assert;
use function PHPUnit\Framework\assertCount;

class ResetPasswordTest extends UseCaseTestCase
{
    private ResetPassword $resetPassword;
    private InMemoryTransport $transport;

    protected function setUp(): void
    {
        parent::setUp();
        $userDao = self::getFromContainer(UserDao::class);
        assert($userDao instanceof UserDao);

        $user = new User(
            'foo',
            'bar',
            'foo.bar@foo.com',
            Locale::EN(),
            Role::USER()
        );
        $userDao->save($user);

        $this->resetPassword = self::getFromContainer(ResetPassword::class);
        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->transport = self::getContainer()->get(AsyncTransport::KEY);
    }

    /**
     * @return iterable<array{string}>
     */
    public function providesValidEmail(): iterable
    {
        yield ['foo.bar@foo.com'];
    }

    /**
     * @dataProvider providesValidEmail
     * @group        User
     */
    public function testDispatchesAnEmail(string $email): void
    {
        $this->resetPassword->resetPassword($email);
        assertCount(1, $this->transport->getSent());
        $envelope = $this->transport->get()[0];
        $message  = $envelope->getMessage();
        assert($message instanceof SendEmailMessage);
    }

    /**
     * @return iterable<array{string}>
     */
    public function providesInvalidEmail(): iterable
    {
        yield ['foo'];
    }

    /**
     * @dataProvider providesInvalidEmail
     * @group        User
     */
    public function testDoesNotDispatchAnEmailIfEmailDoesNotExist(string $email): void
    {
        $this->resetPassword->resetPassword($email);
        assertCount(0, $this->transport->getSent());
    }

    /**
     * @dataProvider providesValidEmail
     * @group        User
     */
    public function testDeletesThePreviousToken(string $email): void
    {
        $resetPasswordTokenDao = self::getFromContainer(ResetPasswordTokenDao::class);
        assert($resetPasswordTokenDao instanceof ResetPasswordTokenDao);

        $this->resetPassword->resetPassword($email);
        assertCount(1, $this->transport->getSent());
        $envelope = $this->transport->get()[0];
        $message  = $envelope->getMessage();
        assert($message instanceof SendEmailMessage);

        $firstResetPasswordToken = $resetPasswordTokenDao->findAll()->first();
        assert($firstResetPasswordToken instanceof ResetPasswordToken);

        $this->resetPassword->resetPassword($email);
        assertCount(2, $this->transport->getSent());
        $envelope = $this->transport->get()[1];
        $message1 = $envelope->getMessage();
        assert($message1 instanceof SendEmailMessage);

        $this->expectException(TDBMException::class);
        $resetPasswordTokenDao->getById($firstResetPasswordToken->getId());
    }
}
