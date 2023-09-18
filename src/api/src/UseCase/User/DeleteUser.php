<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Model\User;
use App\Domain\Storage\ProfilePictureStorage;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Security;

final class DeleteUser
{
    public function __construct(
        private UserDao $userDao,
        private ProfilePictureStorage $profilePictureStorage,
    ) {
    }

    #[Mutation]
    #[Logged]
    #[Security("is_granted('DELETE_USER', user)")]
    public function deleteUser(User $user): bool
    {
        // Remove profile picture (if any).
        $filename = $user->getProfilePicture();
        if ($filename !== null) {
            $this->profilePictureStorage->delete($filename);
        }

        // Cascade = true will also delete the reset
        // password token (if any).
        $this->userDao->delete($user, true);

        return true;
    }
}
