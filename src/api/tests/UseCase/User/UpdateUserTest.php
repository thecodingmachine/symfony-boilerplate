<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Throwable\InvalidModel;
use App\Tests\UseCase\DummyValues;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\CreateUser;
use App\UseCase\User\UpdateUser;

use function assert;
use function dirname;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

class UpdateUserTest extends UseCaseTestCase
{
    private UserDao $userDao;
    private UpdateUser $updateUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userDao    = self::getFromContainer(UserDao::class);
        $this->updateUser = self::getFromContainer(UpdateUser::class);

        $user = new User(
            'foo',
            'bar',
            'user@foo.com',
            Locale::FR(),
            Role::USER()
        );
        $user->setId('1');
        $this->userDao->save($user);
    }

    /**
     * @return iterable<string, array{string, string, string, Locale, Role, ?string}>
     */
    public function providesUserToCreate(): iterable
    {
        yield 'Admin without picture' => ['bar', 'foo', 'bar.foo@baz.com', Locale::EN(), Role::ADMINISTRATOR(), null];

        yield 'User without picture' => ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::USER(), null];

        yield 'User with picture' => ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::USER(), 'foo.png'];
    }

    /**
     * @dataProvider providesUserToCreate
     * @group        User
     */
    public function testUpdatesAUser(
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role,
        ?string $filename
    ): void {
        $storable = null;
        if ($filename !== null) {
            $storable = ProfilePicture::createFromPath(
                dirname(__FILE__) . '/' . $filename
            );
        }

        $user = $this->updateUser->update(
            $this->userDao->getById('1'),
            $firstName,
            $lastName,
            $email,
            $locale,
            $role,
            $storable
        );

        assertEquals($firstName, $user->getFirstName());
        assertEquals($lastName, $user->getLastName());
        assertEquals($email, $user->getEmail());
        assertNull($user->getPassword());
        assertEquals($locale, $user->getLocale());
        assertEquals($role, $user->getRole());

        if ($filename !== null) {
            assertNotNull($user->getProfilePicture());
        } else {
            assertNull($user->getProfilePicture());
        }
    }

    /**
     * @return iterable<string, array{string, string, string, Locale, Role}>
     */
    public function providesInvalidUsers(): iterable
    {
        yield 'Blank first name' => [DummyValues::BLANK, 'bar', 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()];
        yield 'First name > 255' => [DummyValues::CHAR256, 'bar', 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()];
        yield 'Blank last name' => ['foo', DummyValues::BLANK, 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()];
        yield 'Last name > 255' => ['foo', DummyValues::CHAR256, 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()];

        yield 'Existing e-mail' => ['foo', 'far', 'foo@bar.com', Locale::EN(), Role::ADMINISTRATOR()];

        yield 'Invalid e-mail' => ['foo', 'far', 'foo', Locale::EN(), Role::ADMINISTRATOR()];
    }

    /**
     * @dataProvider providesInvalidUsers
     * @group        User
     */
    public function testThrowsAnExceptionIfInvalidUser(
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role
    ): void {
        $createUser = self::getFromContainer(CreateUser::class);
        assert($createUser instanceof CreateUser);

        // We create a user for checking if an
        // email is not unique.
        $createUser->createUser(
            'foo',
            'bar',
            'foo@bar.com',
            Locale::EN(),
            Role::USER()
        );

        $this->expectException(InvalidModel::class);
        $this->updateUser->updateUser(
            $this->userDao->getById('1'),
            $firstName,
            $lastName,
            $email,
            $locale,
            $role
        );
    }
}
