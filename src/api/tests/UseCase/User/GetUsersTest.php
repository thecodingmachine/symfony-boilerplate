<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Filter\SortOrder;
use App\Domain\Enum\Filter\UsersSortBy;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\User;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\GetUsers;

use function assert;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertStringContainsStringIgnoringCase;

class GetUsersTest extends UseCaseTestCase
{
    private GetUsers $getUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $userDao = self::getFromContainer(UserDao::class);
        assert($userDao instanceof UserDao);
        $this->getUsers = self::getFromContainer(GetUsers::class);

        $user = new User(
            'a',
            'a',
            'a.a@a.a',
            Locale::EN(),
            Role::ADMINISTRATOR()
        );
        $userDao->save($user);

        $user = new User(
            'b',
            'b',
            'b.b@b.b',
            Locale::EN(),
            Role::USER()
        );
        $userDao->save($user);

        $user = new User(
            'c',
            'c',
            'c.c@c.c',
            Locale::EN(),
            Role::USER()
        );
        $userDao->save($user);
    }

    /**
     * @group        User
     */
    public function testFindsAllUsers(): void
    {
        $result = $this->getUsers->users();
        assertCount(3, $result);
    }

    /**
     * @return iterable<array{string}>
     */
    public function providesSearchQueries(): iterable
    {
        yield ['a'];
        yield ['b'];
        yield ['c'];
    }

    /**
     * @dataProvider providesSearchQueries
     * @group        User
     */
    public function testFiltersUsersWithAGenericSearch(string $search): void
    {
        $result = $this->getUsers->users($search);
        assertCount(1, $result);

        $user = $result->first();
        assert($user instanceof User);
        assertStringContainsStringIgnoringCase($search, $user->getFirstName());
        assertStringContainsStringIgnoringCase($search, $user->getLastName());
        assertStringContainsStringIgnoringCase($search, $user->getEmail());
    }

    /**
     * @return iterable<array{Role}>
     */
    public function providesRoles(): iterable
    {
        yield [Role::ADMINISTRATOR()];
        yield [Role::USER()];
    }

    /**
     * @dataProvider providesRoles
     * @group        User
     */
    public function testFiltersUsersByRole(Role $role): void
    {
        $result = $this->getUsers->users(null, $role);

        if ($role->equals(Role::ADMINISTRATOR())) {
            assertCount(1, $result);
        }

        if ($role->equals(Role::USER())) {
            assertCount(2, $result);
        }

        $user = $result->first();
        assert($user instanceof User);
        assertEquals($role, $user->getRole());
    }

    /**
     * @return iterable<array{SortOrder}>
     */
    public function providesSortOrder(): iterable
    {
        yield [SortOrder::ASC()];
        yield [SortOrder::DESC()];
    }

    /**
     * @dataProvider providesSortOrder
     * @group        User
     */
    public function testSortsUsersByFirstName(SortOrder $sortOrder): void
    {
        $result = $this->getUsers->users(null, null, UsersSortBy::FIRST_NAME(), $sortOrder);
        assertCount(3, $result);

        /** @var User[] $users */
        $users = $result->toArray();
        if ($sortOrder->equals(SortOrder::ASC())) {
            assertStringContainsStringIgnoringCase('a', $users[0]->getFirstName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getFirstName());
            assertStringContainsStringIgnoringCase('c', $users[2]->getFirstName());
        } else {
            assertStringContainsStringIgnoringCase('a', $users[2]->getFirstName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getFirstName());
            assertStringContainsStringIgnoringCase('c', $users[0]->getFirstName());
        }
    }

    /**
     * @dataProvider providesSortOrder
     * @group        User
     */
    public function testSortsUsersByLastName(SortOrder $sortOrder): void
    {
        $result = $this->getUsers->users(null, null, UsersSortBy::LAST_NAME(), $sortOrder);
        assertCount(3, $result);

        /** @var User[] $users */
        $users = $result->toArray();
        if ($sortOrder->equals(SortOrder::ASC())) {
            assertStringContainsStringIgnoringCase('a', $users[0]->getLastName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getLastName());
            assertStringContainsStringIgnoringCase('c', $users[2]->getLastName());
        } else {
            assertStringContainsStringIgnoringCase('a', $users[2]->getLastName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getLastName());
            assertStringContainsStringIgnoringCase('c', $users[0]->getLastName());
        }
    }

    /**
     * @dataProvider providesSortOrder
     * @group        User
     */
    public function testSortsUsersByEmail(SortOrder $sortOrder): void
    {
        $result = $this->getUsers->users(null, null, UsersSortBy::EMAIL(), $sortOrder);
        assertCount(3, $result);

        /** @var User[] $users */
        $users = $result->toArray();

        if ($sortOrder->equals(SortOrder::ASC())) {
            assertStringContainsStringIgnoringCase('a', $users[0]->getEmail());
            assertStringContainsStringIgnoringCase('b', $users[1]->getEmail());
            assertStringContainsStringIgnoringCase('c', $users[2]->getEmail());
        } else {
            assertStringContainsStringIgnoringCase('a', $users[2]->getEmail());
            assertStringContainsStringIgnoringCase('b', $users[1]->getEmail());
            assertStringContainsStringIgnoringCase('c', $users[0]->getEmail());
        }
    }
}
