<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Domain\Model\User;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Security;

final class GetUser
{
    /**
     * @Query
     * @Logged
     * @Security("is_granted('GET_USER', user1)")
     */
    public function user(User $user1): User
    {
        // GraphQLite black magic.
        return $user1;
    }
}
