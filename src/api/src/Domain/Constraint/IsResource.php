<?php

declare(strict_types=1);

namespace App\Domain\Constraint;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
final class IsResource extends Constraint
{
    public string $message;
}
