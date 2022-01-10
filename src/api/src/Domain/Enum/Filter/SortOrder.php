<?php

declare(strict_types=1);

namespace App\Domain\Enum\Filter;

use MyCLabs\Enum\Enum;
use TheCodingMachine\GraphQLite\Annotations as GraphQLite;

/**
 * @method static SortOrder ASC()
 * @method static SortOrder DESC()
 */
#[GraphQLite\EnumType]
final class SortOrder extends Enum
{
    private const ASC  = 'ASC';
    private const DESC = 'DESC';
}
