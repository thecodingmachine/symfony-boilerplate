<?php

declare(strict_types=1);

namespace App\Domain\Enum\Filter;

use MyCLabs\Enum\Enum;
use TheCodingMachine\GraphQLite\Annotations as GraphQLite;

/**
 * @method static UsersSortBy FIRST_NAME()
 * @method static UsersSortBy LAST_NAME()
 * @method static UsersSortBy EMAIL()
 */
#[GraphQLite\EnumType]
final class UsersSortBy extends Enum
{
    /**
     * @var string
     */
    private const FIRST_NAME = 'first_name';
    /**
     * @var string
     */
    private const LAST_NAME  = 'last_name';
    /**
     * @var string
     */
    private const EMAIL      = 'email';
}
