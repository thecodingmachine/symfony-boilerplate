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
    private UserDao $userDao;
    private ProfilePictureStorage $profilePictureStorage;

    public function __construct(
        UserDao $userDao,
        ProfilePictureStorage $profilePictureStorage
    ) {
        $this->userDao               = $userDao;
        $this->profilePictureStorage = $profilePictureStorage;
    }

    /**
     * @Mutation
     * @Logged
     * @Security("is_granted('DELETE_USER', user1)")
     */
    public function deleteUser(User $user1): bool
    {
        // Remove profile picture (if any).
        $filename = $user1->getProfilePicture();
        if ($filename !== null) {
            $this->profilePictureStorage->delete($filename);
        }

        // Cascade = true will also delete the reset
        // password token (if any).
        $this->userDao->delete($user1, true);

        return true;
    }
}
