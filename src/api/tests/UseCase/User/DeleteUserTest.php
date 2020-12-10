<?php

declare(strict_types=1);

use App\Domain\Dao\ResetPasswordTokenDao;
use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Storage\ProfilePictureStorage;
use App\UseCase\User\DeleteUser;
use App\UseCase\User\ResetPassword\ResetPassword;
use App\UseCase\User\UpdateProfilePicture;
use TheCodingMachine\TDBM\TDBMException;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertFalse;

beforeEach(function (): void {
    $userDao = self::$container->get(UserDao::class);
    assert($userDao instanceof UserDao);

    $user = new User(
        'foo',
        'bar',
        'user@foo.com',
        Locale::EN(),
        Role::USER()
    );
    $user->setId('1');
    $userDao->save($user);
});

it(
    'deletes the user',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $deleteUser = self::$container->get(DeleteUser::class);
        assert($deleteUser instanceof DeleteUser);

        $user = $userDao->getById('1');
        $deleteUser->deleteUser($user);

        $userDao->getById($user->getId());
    }
)
    ->throws(TDBMException::class)
    ->group('user');

it(
    'deletes the profile picture',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $updateProfilePicture = self::$container->get(UpdateProfilePicture::class);
        assert($updateProfilePicture instanceof UpdateProfilePicture);
        $profilePictureStorage = self::$container->get(ProfilePictureStorage::class);
        assert($profilePictureStorage instanceof ProfilePictureStorage);
        $deleteUser = self::$container->get(DeleteUser::class);
        assert($deleteUser instanceof DeleteUser);

        $user     = $userDao->getById('1');
        $storable = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.jpg'
        );
        $user     = $updateProfilePicture->update($user, $storable);
        $filename = $user->getProfilePicture();

        $deleteUser->deleteUser($user);

        assertFalse($profilePictureStorage->fileExists($filename));
        $userDao->getById($user->getId());
    }
)
    ->throws(TDBMException::class)
    ->group('user');

it(
    'deletes the reset password token',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $resetPassword = self::$container->get(App\UseCase\User\ResetPassword\ResetPassword::class);
        assert($resetPassword instanceof ResetPassword);
        $deleteUser = self::$container->get(DeleteUser::class);
        assert($deleteUser instanceof DeleteUser);
        $resetPasswordTokenDao = self::$container->get(ResetPasswordTokenDao::class);
        assert($resetPasswordTokenDao instanceof ResetPasswordTokenDao);

        $user = $userDao->getById('1');
        $resetPassword->resetPassword($user->getEmail());
        assertCount(1, $resetPasswordTokenDao->findAll());

        $deleteUser->deleteUser($user);

        assertCount(0, $resetPasswordTokenDao->findAll());
        $resetPasswordTokenDao->getById($user->getId());
    }
)
    ->throws(TDBMException::class)
    ->group('user');
