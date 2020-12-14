<?php

declare(strict_types=1);

namespace App\UseCase\User;

use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Query;

final class FailIfNotAuthenticated
{
    public function __construct()
    {
        // Required so that Doctrine Coding Standard does not complain.
    }

    /**
     * @Query
     * @Logged
     */
    public function failIfNotAuthenticated(): bool
    {
        return true;
    }
}
