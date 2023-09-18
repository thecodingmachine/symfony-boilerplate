<?php

declare(strict_types=1);

namespace App\Domain\Enum;

use MyCLabs\Enum\Enum;
use TheCodingMachine\GraphQLite\Annotations as GraphQLite;

/**
 * @method static Locale EN()
 * @method static Locale FR()
 */
#[GraphQLite\EnumType]
final class Locale extends Enum
{
    /**
     * @var string
     */
    private const EN = 'en';
    /**
     * @var string
     */
    private const FR = 'fr';

    /**
     * @return string[]
     */
    public static function valuesAsArray(): array
    {
        return [
            self::EN,
            self::FR,
        ];
    }
}
