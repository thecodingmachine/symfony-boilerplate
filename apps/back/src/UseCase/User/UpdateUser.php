<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\Request\User\UpdateUserDto;
use App\Entity\User;
use App\UseCase\Storage\StoreFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUser
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly StoreFile $storeFile,
    ) {
    }

    public function updateUser(User $user, UpdateUserDto $userDto): User
    {
        $user->setEmail($userDto->getEmail());

        if ($userDto->getPassword()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $userDto->getPassword()));
        }

        if ($userDto->getProfilePicture() !== null) {
            $pictureFile = $this->storeFile->storeUploadedUserPicture($userDto->getProfilePicture(), $user);
            $user->setProfilePicture($pictureFile);
        }

        return $user;
    }
}
