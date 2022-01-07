<?php

declare(strict_types=1);

namespace App\Domain\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class Unicity extends Constraint
{
    public string $message;
    public string $table;
    public string $column;
    /** @var class-string $className */
    public string $className;

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [
            'message',
            'table',
            'column',
            'className',
        ];
    }
}
