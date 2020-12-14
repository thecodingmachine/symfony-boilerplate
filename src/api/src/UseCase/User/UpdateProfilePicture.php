<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Storage\ProfilePictureStorage;
use App\Domain\Throwable\InvalidModel;
use App\Domain\Throwable\InvalidStorable;
use Psr\Http\Message\UploadedFileInterface;
use TheCodingMachine\GraphQLite\Annotations\InjectUser;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class UpdateProfilePicture
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
     * @throws InvalidStorable
     * @throws InvalidModel
     *
     * @Mutation
     * @Logged
     * @InjectUser(for="$user")
     */
    public function updateProfilePicture(
        User $user,
        UploadedFileInterface $profilePicture
    ): User {
        $storable = ProfilePicture::createFromUploadedFile($profilePicture);

        return $this->update($user, $storable);
    }

    /**
     * @throws InvalidStorable
     * @throws InvalidModel
     */
    public function update(
        User $user,
        ProfilePicture $profilePicture
    ): User {
        // We validate the new profile picture before
        // doing anything else.
        $this->profilePictureStorage->validate($profilePicture);

        // Remove previous profile picture (if any).
        $previousFilename = $user->getProfilePicture();
        if ($previousFilename !== null) {
            $this->profilePictureStorage->delete($previousFilename);
        }

        // Store new profile picture.
        $filename = $this->profilePictureStorage->write($profilePicture);
        $user->setProfilePicture($filename);

        $this->userDao->save($user);

        return $user;
    }
}
