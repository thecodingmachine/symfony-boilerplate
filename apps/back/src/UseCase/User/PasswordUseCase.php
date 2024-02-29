<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\Request\User\PasswordDtoInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordUseCase
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function updatePassword(User $user, PasswordDtoInterface $passwordDto): bool
    {
        if ($passwordDto->getPassword()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $passwordDto->getPassword()));

            return true;
        }

        return false;
    }
}
