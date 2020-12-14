<?php

declare(strict_types=1);

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Storage\ProfilePictureStorage;
use App\Domain\Throwable\InvalidStorable;
use App\UseCase\User\UpdateProfilePicture;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

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

it('updates a profile picture', function (?string $filename): void {
    $userDao = self::$container->get(UserDao::class);
    assert($userDao instanceof UserDao);
    $updateProfilePicture = self::$container->get(UpdateProfilePicture::class);
    assert($updateProfilePicture instanceof UpdateProfilePicture);
    $profilePictureStorage = self::$container->get(ProfilePictureStorage::class);
    assert($profilePictureStorage instanceof ProfilePictureStorage);

    $user     = $userDao->getById('1');
    $storable = ProfilePicture::createFromPath(
        dirname(__FILE__) . '/' . $filename
    );
    $user     = $updateProfilePicture->update($user, $storable);

    assertTrue($profilePictureStorage->fileExists($user->getProfilePicture()));
})
    ->with([
        'foo.png',
        'foo.jpg',
    ])
    ->group('user');

it(
    'deletes previous profile picture',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $updateProfilePicture = self::$container->get(UpdateProfilePicture::class);
        assert($updateProfilePicture instanceof UpdateProfilePicture);
        $profilePictureStorage = self::$container->get(ProfilePictureStorage::class);
        assert($profilePictureStorage instanceof ProfilePictureStorage);

        $user             = $userDao->getById('1');
        $storable         = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.jpg'
        );
        $user             = $updateProfilePicture->update($user, $storable);
        $previousFilename = $user->getProfilePicture();

        assertNotNull($previousFilename);

        $storable = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.jpg'
        );
        $updateProfilePicture->update($user, $storable);

        assertFalse($profilePictureStorage->fileExists($previousFilename));
    }
)
    ->group('user');

it(
    'throws an exception in valid profile picture',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $updateProfilePicture = self::$container->get(UpdateProfilePicture::class);
        assert($updateProfilePicture instanceof UpdateProfilePicture);

        $user     = $userDao->getById('1');
        $storable = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.txt'
        );

        $updateProfilePicture->update($user, $storable);
    }
)
    ->throws(InvalidStorable::class)
    ->group('user');
