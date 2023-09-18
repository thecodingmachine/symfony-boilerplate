<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Model\User;
use App\Domain\Throwable\InvalidModel;
use TheCodingMachine\GraphQLite\Annotations\InjectUser;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

use function strval;

final class UpdateLocale
{
    public function __construct(
        private UserDao $userDao,
    ) {
    }

    /**
     * @throws InvalidModel
     */
    #[Mutation]
    #[Logged]
    public function updateLocale(
        #[InjectUser] User $user,
        Locale $locale
    ): User {
        $user->setLocale(strval($locale));
        $this->userDao->save($user);

        return $user;
    }
}
