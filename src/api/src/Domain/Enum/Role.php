<?php

declare(strict_types=1);

namespace App\Domain\Enum;

use MyCLabs\Enum\Enum;
use TheCodingMachine\GraphQLite\Annotations as GraphQLite;

/**
 * @method static Role ADMINISTRATOR()
 * @method static Role USER()
 */
#[GraphQLite\EnumType]
final class Role extends Enum
{
    /**
     * @var string
     */
    private const ADMINISTRATOR = 'ADMINISTRATOR';
    /**
     * @var string
     */
    private const USER          = 'USER';

    /**
     * @return string[]
     */
    public static function valuesAsArray(): array
    {
        return [
            self::ADMINISTRATOR,
            self::USER,
        ];
    }

    public static function getSymfonyRole(Role $role): string
    {
        return 'ROLE_' . $role;
    }
}
