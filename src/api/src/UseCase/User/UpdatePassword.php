<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Domain\Dao\ResetPasswordTokenDao;
use App\Domain\Dao\UserDao;
use App\Domain\Model\Proxy\PasswordProxy;
use App\Domain\Model\ResetPasswordToken;
use App\Domain\Model\User;
use App\Domain\Throwable\InvalidModel;
use App\UseCase\User\VerifyResetPasswordToken\InvalidResetPasswordTokenId;
use App\UseCase\User\VerifyResetPasswordToken\ResetPasswordTokenExpired;
use App\UseCase\User\VerifyResetPasswordToken\VerifyResetPasswordToken;
use App\UseCase\User\VerifyResetPasswordToken\WrongResetPasswordToken;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class UpdatePassword
{
    public function __construct(
        private VerifyResetPasswordToken $verifyResetPasswordToken,
        private ResetPasswordTokenDao $resetPasswordTokenDao,
        private UserDao $userDao,
    ) {
    }

    /**
     * @throws InvalidResetPasswordTokenId
     * @throws WrongResetPasswordToken
     * @throws ResetPasswordTokenExpired
     * @throws InvalidModel
     */
    #[Mutation]
    public function updatePassword(
        ResetPasswordToken $resetPasswordToken,
        string $plainToken,
        string $newPassword,
        string $passwordConfirmation
    ): User {
        $this->verifyResetPasswordToken->verifyResetPasswordToken(
            $resetPasswordToken->getId(),
            $plainToken
        );

        $passwordProxy = new PasswordProxy($newPassword, $passwordConfirmation);
        $user          = $resetPasswordToken->getUser();

        $this->userDao->updatePassword($user, $passwordProxy);
        $this->resetPasswordTokenDao->delete($resetPasswordToken);

        return $user;
    }
}
