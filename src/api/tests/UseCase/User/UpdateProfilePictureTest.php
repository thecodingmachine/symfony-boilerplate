<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Storage\ProfilePictureStorage;
use App\Domain\Throwable\InvalidStorable;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\UpdateProfilePicture;

use function dirname;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertTrue;

class UpdateProfilePictureTest extends UseCaseTestCase
{
    private UserDao $userDao;
    private UpdateProfilePicture $updateProfilePicture;
    private ProfilePictureStorage $profilePictureStorage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userDao               = self::getFromContainer(UserDao::class);
        $this->updateProfilePicture  = self::getFromContainer(UpdateProfilePicture::class);
        $this->profilePictureStorage = self::getFromContainer(ProfilePictureStorage::class);

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
     * @return iterable<array{string}>
     */
    public function providesValidFiles(): iterable
    {
        yield ['foo.png'];

        yield ['foo.jpg'];
    }

    /**
     * @dataProvider providesValidFiles
     * @group        User
     */
    public function testUpdatesAProfilePicture(?string $filename): void
    {
        $user     = $this->userDao->getById('1');
        $storable = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/' . $filename
        );
        $user     = $this->updateProfilePicture->update($user, $storable);

        assertTrue($this->profilePictureStorage->fileExists($user->getProfilePicture()));
    }

    /**
     * @group        User
     */
    public function testDeletesPreviousProfilePicture(): void
    {
        $user             = $this->userDao->getById('1');
        $storable         = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.jpg'
        );
        $user             = $this->updateProfilePicture->update($user, $storable);
        $previousFilename = $user->getProfilePicture();

        assertNotNull($previousFilename);

        $storable = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.jpg'
        );
        $this->updateProfilePicture->update($user, $storable);

        assertFalse($this->profilePictureStorage->fileExists($previousFilename));
    }

    /**
     * @group        User
     */
    public function testThrowsAnExceptionInValidProfilePicture(): void
    {
        $user     = $this->userDao->getById('1');
        $storable = ProfilePicture::createFromPath(
            dirname(__FILE__) . '/foo.txt'
        );

        $this->expectException(InvalidStorable::class);
        $this->updateProfilePicture->update($user, $storable);
    }
}
