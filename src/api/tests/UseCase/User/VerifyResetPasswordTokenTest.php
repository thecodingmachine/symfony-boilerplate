<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\ResetPasswordTokenDao;
use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\ResetPasswordToken;
use App\Domain\Model\User;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\VerifyResetPasswordToken\InvalidResetPasswordTokenId;
use App\UseCase\User\VerifyResetPasswordToken\ResetPasswordTokenExpired;
use App\UseCase\User\VerifyResetPasswordToken\VerifyResetPasswordToken;
use App\UseCase\User\VerifyResetPasswordToken\WrongResetPasswordToken;
use DateInterval;
use Safe\DateTimeImmutable;

use function PHPUnit\Framework\assertTrue;

class VerifyResetPasswordTokenTest extends UseCaseTestCase
{
    private ResetPasswordTokenDao $resetPasswordTokenDao;
    private VerifyResetPasswordToken $verifyResetPasswordToken;
    private const TOKEN = 'foo';

    protected function setUp(): void
    {
        parent::setUp();
        $userDao                        = self::getFromContainer(UserDao::class);
        $this->resetPasswordTokenDao    = self::getFromContainer(ResetPasswordTokenDao::class);
        $this->verifyResetPasswordToken = self::getFromContainer(VerifyResetPasswordToken::class);

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
            self::TOKEN,
            $validUntil
        );
        $resetPasswordToken->setId('1');
        $this->resetPasswordTokenDao->save($resetPasswordToken);
    }

    /**
     * @group        User
     */
    public function testReturnsTrueIfValidResetPasswordToken(): void
    {
        $result = $this->verifyResetPasswordToken->verifyResetPasswordToken(
            '1',
            self::TOKEN
        );

        assertTrue($result);
    }

    /**
     * @group        User
     */
    public function testThrowsAnExceptionIfInvalidResetPasswordTokenId(): void
    {
        $this->expectException(InvalidResetPasswordTokenId::class);
        $this->verifyResetPasswordToken->verifyResetPasswordToken(
            'foo',
            self::TOKEN
        );
    }

    /**
     * @group        User
     */
    public function testThrowsAnExceptionIfWrongToken(): void
    {
        $this->expectException(WrongResetPasswordToken::class);
        $this->verifyResetPasswordToken->verifyResetPasswordToken(
            '1',
            self::TOKEN . '-wrong'
        );
    }

    /**
     * @group        User
     */
    public function testThrowsAnExceptionIfTokenExpired(): void
    {
        $resetPasswordToken = $this->resetPasswordTokenDao->getById('1');

        $validUntil = new DateTimeImmutable();
        $validUntil = $validUntil->sub(new DateInterval('P1D'));
        $resetPasswordToken->setValidUntil($validUntil);
        $this->resetPasswordTokenDao->save($resetPasswordToken);

        $this->expectException(ResetPasswordTokenExpired::class);
        $this->verifyResetPasswordToken->verifyResetPasswordToken(
            '1',
            self::TOKEN
        );
    }
}
