<?php

declare(strict_types=1);

namespace App\Tests\UseCase\User;

use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Tests\UseCase\UseCaseTestCase;
use App\UseCase\User\CreateUser;
use App\UseCase\User\UpdateLocale;

use function assert;
use function PHPUnit\Framework\assertEquals;

class UpdateLocaleTest extends UseCaseTestCase
{
    /**
     * @group        User
     */
    public function testUpdatesTheLocale(): void
    {
        $createUser = self::getFromContainer(CreateUser::class);
        assert($createUser instanceof CreateUser);
        $updateLocale = self::getFromContainer(UpdateLocale::class);
        assert($updateLocale instanceof UpdateLocale);

        $user = $createUser->createUser(
            'foo',
            'bar',
            'foo.bar@baz.com',
            Locale::EN(),
            Role::ADMINISTRATOR()
        );

        $updateLocale->updateLocale($user, Locale::FR());

        assertEquals(Locale::FR(), $user->getLocale());
    }
}
