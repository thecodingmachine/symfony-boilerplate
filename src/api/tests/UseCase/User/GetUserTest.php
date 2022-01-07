<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\User;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\GetUser;

use function assert;
use function PHPUnit\Framework\assertEquals;

class GetUserTest extends UseCaseTestCase
{
    /**
     * @group        User
     */
    public function testGetsAUser(): void
    {
        $userDao = self::getFromContainer(UserDao::class);
        assert($userDao instanceof UserDao);
        $getUser = self::getFromContainer(GetUser::class);
        assert($getUser instanceof GetUser);

        $user = new User(
            'foo',
            'bar',
            'foo.bar@foo.com',
            Locale::EN(),
            Role::ADMINISTRATOR()
        );
        $userDao->save($user);

        $foundUser = $getUser->user($user);
        assertEquals($user, $foundUser);
    }
}
