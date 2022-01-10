<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\ResetPasswordTokenDao;
use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Storage\ProfilePictureStorage;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\DeleteUser;
use App\UseCase\User\ResetPassword\ResetPassword;
use App\UseCase\User\UpdateProfilePicture;
use TheCodingMachine\TDBM\TDBMException;

use function assert;
use function dirname;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertFalse;

class DeleteUserTest extends UseCaseTestCase
{
    private UserDao $userDao;
    private DeleteUser $deleteUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userDao    = self::getFromContainer(UserDao::class);
        $this->deleteUser = self::getFromContainer(DeleteUser::class);

        $user = new User(
            'foo',
            'bar',
            'user@foo.com',
            Locale::EN(),
            Role::USER()
        );
        $user->setId('1');
        $this->userDao->save($user);
    }

    /**
     * @group        test
     */
    public function testDeletesTheUser(): void
    {
        $user = $this->userDao->getById('1');
        $this->deleteUser->deleteUser($user);

        $this->expectException(TDBMException::class);
        $this->userDao->getById($user->getId());
    }

    /**
     * @group        test
     */
    public function testDeletesTheProfilePicture(): void
    {
        $updateProfilePicture = self::getFromContainer(UpdateProfilePicture::class);
        assert($updateProfilePicture instanceof UpdateProfilePicture);
        $profilePictureStorage = self::getFromContainer(ProfilePictureStorage::class);
        assert($profilePictureStorage instanceof ProfilePictureStorage);

        $user     = $this->userDao->getById('1');
        $storable = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.jpg'
        );
        $user     = $updateProfilePicture->update($user, $storable);
        $filename = $user->getProfilePicture();

        $this->deleteUser->deleteUser($user);

        assertFalse($profilePictureStorage->fileExists($filename));
        $this->expectException(TDBMException::class);
        $this->userDao->getById($user->getId());
    }

    /**
     * @group        test
     */
    public function testDeletesTheResetPasswordToken(): void
    {
        $resetPassword = self::getFromContainer(ResetPassword::class);
        assert($resetPassword instanceof ResetPassword);
        $resetPasswordTokenDao = self::getFromContainer(ResetPasswordTokenDao::class);
        assert($resetPasswordTokenDao instanceof ResetPasswordTokenDao);

        $user = $this->userDao->getById('1');
        $resetPassword->resetPassword($user->getEmail());
        assertCount(1, $resetPasswordTokenDao->findAll());

        $this->deleteUser->deleteUser($user);

        assertCount(0, $resetPasswordTokenDao->findAll());
        $this->expectException(TDBMException::class);
        $resetPasswordTokenDao->getById($user->getId());
    }
}
