<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\Request\UpdateUserDto;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUser
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function updateUser(User $user, UpdateUserDto $userDto): User
    {
        $user->setEmail($userDto->getEmail());

        if ($userDto->getPassword()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $userDto->getPassword()));
        }

        return $user;
    }
}
