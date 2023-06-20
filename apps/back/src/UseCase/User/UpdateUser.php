<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\UserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UpdateUser
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function updateUser(User $user, UserDto $userDto): User
    {
        $user->setEmail($userDto->getEmail());

        if ($userDto->getPassword()) {
            $user->setPassword($userDto->getPassword());
        }

        $this->entityManager->persist($user);

        return $user;
    }
}
