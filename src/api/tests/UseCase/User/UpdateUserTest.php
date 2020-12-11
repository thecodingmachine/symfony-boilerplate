<?php

declare(strict_types=1);

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Model\User;
use App\Domain\Throwable\InvalidModel;
use App\Tests\UseCase\DummyValues;
use App\UseCase\User\CreateUser;
use App\UseCase\User\UpdateUser;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

beforeEach(function (): void {
    $userDao = self::$container->get(UserDao::class);
    assert($userDao instanceof UserDao);

    $user = new User(
        'foo',
        'bar',
        'user@foo.com',
        Locale::FR(),
        Role::USER()
    );
    $user->setId('1');
    $userDao->save($user);
});

it(
    'updates a user',
    function (
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role,
        ?string $filename
    ): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $updateUser = self::$container->get(UpdateUser::class);
        assert($updateUser instanceof UpdateUser);

        $storable = null;
        if ($filename !== null) {
            $storable = ProfilePicture::createFromPath(
                dirname(__FILE__) . '/' . $filename
            );
        }

        $user = $updateUser->update(
            $userDao->getById('1'),
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
)
    ->with([
        ['bar', 'foo', 'bar.foo@baz.com', Locale::EN(), Role::ADMINISTRATOR(), null],
        ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::USER(), null],
        ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::USER(), 'foo.png'],
    ])
    ->group('user');

it(
    'throws an exception if invalid user',
    function (
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role
    ): void {
        $createUser = self::$container->get(CreateUser::class);
        assert($createUser instanceof CreateUser);
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $updateUser = self::$container->get(UpdateUser::class);
        assert($updateUser instanceof UpdateUser);

        // We create a user for checking if an
        // email is not unique.
        $createUser->createUser(
            'foo',
            'bar',
            'foo@bar.com',
            Locale::EN(),
            Role::USER()
        );

        $updateUser->updateUser(
            $userDao->getById('1'),
            $firstName,
            $lastName,
            $email,
            $locale,
            $role
        );
    }
)
    ->with([
        // Blank first name.
        [DummyValues::BLANK, 'bar', 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // First name > 255.
        [DummyValues::CHAR256, 'bar', 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Blank last name.
        ['foo', DummyValues::BLANK, 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Last name > 255.
        ['foo', DummyValues::CHAR256, 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Existing e-mail.
        ['foo', 'far', 'foo@bar.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Invalid e-mail.
        ['foo', 'far', 'foo', Locale::EN(), Role::ADMINISTRATOR()],
    ])
    ->throws(InvalidModel::class)
    ->group('user');
