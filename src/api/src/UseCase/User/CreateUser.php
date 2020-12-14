<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Throwable\InvalidModel;
use App\Domain\Throwable\InvalidStorable;
use App\UseCase\User\ResetPassword\ResetPassword;
use Psr\Http\Message\UploadedFileInterface;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Security;

final class CreateUser
{
    private UserDao $userDao;
    private UpdateProfilePicture $updateProfilePicture;
    private ResetPassword $resetPassword;

    public function __construct(
        UserDao $userDao,
        UpdateProfilePicture $updateProfilePicture,
        ResetPassword $resetPassword
    ) {
        $this->userDao              = $userDao;
        $this->updateProfilePicture = $updateProfilePicture;
        $this->resetPassword        = $resetPassword;
    }

    /**
     * @throws InvalidModel
     * @throws InvalidStorable
     *
     * @Mutation
     * @Logged
     * @Security("is_granted('ROLE_ADMINISTRATOR')")
     */
    public function createUser(
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role,
        ?UploadedFileInterface $profilePicture = null
    ): User {
        $storable = null;
        if ($profilePicture !== null) {
            $storable = ProfilePicture::createFromUploadedFile($profilePicture);
        }

        return $this->create(
            $firstName,
            $lastName,
            $email,
            $locale,
            $role,
            $storable
        );
    }

    /**
     * @throws InvalidModel
     * @throws InvalidStorable
     */
    public function create(
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role,
        ?ProfilePicture $profilePicture = null
    ): User {
        $user = new User(
            $firstName,
            $lastName,
            $email,
            $locale,
            $role
        );

        if ($profilePicture === null) {
            $this->userDao->save($user);
            $this->resetPassword->resetPassword($email);

            return $user;
        }

        $this->userDao->validate($user);
        $user = $this
            ->updateProfilePicture
            ->update(
                $user,
                $profilePicture
            );
        $this->resetPassword->resetPassword($email);

        return $user;
    }
}
