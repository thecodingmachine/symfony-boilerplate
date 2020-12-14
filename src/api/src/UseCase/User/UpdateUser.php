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
use Psr\Http\Message\UploadedFileInterface;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Security;

use function strval;

final class UpdateUser
{
    private UserDao $userDao;
    private UpdateProfilePicture $updateProfilePicture;

    public function __construct(
        UserDao $userDao,
        UpdateProfilePicture $updateProfilePicture
    ) {
        $this->userDao              = $userDao;
        $this->updateProfilePicture = $updateProfilePicture;
    }

    /**
     * @throws InvalidModel
     * @throws InvalidStorable
     *
     * @Mutation
     * @Logged
     * @Security("is_granted('ROLE_ADMINISTRATOR')")
     */
    public function updateUser(
        User $user,
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

        return $this->update(
            $user,
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
    public function update(
        User $user,
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role,
        ?ProfilePicture $profilePicture = null
    ): User {
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setLocale(strval($locale));
        $user->setRole(strval($role));

        if ($profilePicture === null) {
            $this->userDao->save($user);

            return $user;
        }

        $this->userDao->validate($user);

        return $this
            ->updateProfilePicture
            ->update(
                $user,
                $profilePicture
            );
    }
}
