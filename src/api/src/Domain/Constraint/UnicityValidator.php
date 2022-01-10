<?php

declare(strict_types=1);

namespace App\Domain\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use TheCodingMachine\TDBM\ResultIterator;
use TheCodingMachine\TDBM\TDBMService;

use function get_debug_type;

final class UnicityValidator extends ConstraintValidator
{
    private TDBMService $tdbmService;

    public function __construct(TDBMService $tdbmService)
    {
        $this->tdbmService = $tdbmService;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof Unicity) {
            throw new UnexpectedTypeException($constraint, Unicity::class);
        }

        if (empty($constraint->message)) {
            throw new ConstraintDefinitionException(get_debug_type($constraint) . ' message argument is empty');
        }

        if (empty($constraint->table)) {
            throw new ConstraintDefinitionException(get_debug_type($constraint) . ' table argument is empty');
        }

        if (empty($constraint->column)) {
            throw new ConstraintDefinitionException(get_debug_type($constraint) . ' column argument is empty');
        }

        if (empty($constraint->className)) {
            throw new ConstraintDefinitionException(get_debug_type($constraint) . ' className argument is empty');
        }

        $getterValue = 'get' . $constraint->column;
        $getterId    = 'getid';

        $existingObject = $this->tdbmService->findObject(
            mainTable            : $constraint->table,
            filter               : [$constraint->column . ' = :value'],
            parameters           : ['value' => $value->$getterValue(),],
            additionalTablesFetch: [],
            className            : $constraint->className,
            resultIteratorClass  : ResultIterator::class
        );

        if ($existingObject === null) {
            return;
        }

        if ($existingObject->$getterId() === $value->$getterId()) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->atPath($constraint->column)
            ->addViolation()
        ;
    }
}
