<?php

declare(strict_types=1);

namespace App\Domain\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static Role ADMINISTRATOR()
 * @method static Role USER()
 */
final class Role extends Enum
{
    private const ADMINISTRATOR = 'ADMINISTRATOR';
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
