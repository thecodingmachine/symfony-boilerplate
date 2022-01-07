<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Throwable\InvalidModel;
use App\Tests\UseCase\DummyValues;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\CreateUser;

use function dirname;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

class CreateUserTest extends UseCaseTestCase
{
    private CreateUser $createUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createUser = self::getFromContainer(CreateUser::class);
    }

    /**
     * @return iterable<string, array{string, string, string, Locale, Role, ?string}>
     */
    public function providesValidUsers(): iterable
    {
        yield 'Admin' => ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::ADMINISTRATOR(), null];
        yield 'User' => ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::USER(), 'foo.png'];
    }

    /**
     * @dataProvider providesValidUsers
     * @group        test
     */
    public function testCreatesAUser(
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

        $user = $this->createUser->create(
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
        // We create a user for checking if an
        // email is not unique.
        $this->createUser->createUser(
            'foo',
            'bar',
            'foo@bar.com',
            Locale::EN(),
            Role::USER()
        );

        $this->expectException(InvalidModel::class);
        $this->createUser->createUser(
            $firstName,
            $lastName,
            $email,
            $locale,
            $role
        );
    }
}
