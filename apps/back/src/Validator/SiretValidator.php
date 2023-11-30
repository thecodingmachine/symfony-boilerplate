<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class SiretValidator extends ConstraintValidator
{
    private function isSiret(string $siret): bool
    {
        // Supprimer les espaces éventuels
        $siret = str_replace(' ', '', $siret);

        // Vérifier la longueur du SIRET (14 caractères)
        if (\strlen($siret) !== 14) {
            return false;
        }

        // Calculer la clé de contrôle
        $sum = 0;
        for ($i = 0; $i < 14; $i++) {
            $digit = (int) $siret[$i];

            // Multiplier par 2 les chiffres d'indice pair
            if ($i % 2 === 0) {
                $digit *= 2;

                // Soustraire 9 si le résultat est supérieur à 9
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
        }

        // Vérifier si la somme est un multiple de 10
        return $sum % 10 === 0;
    }

    /** @param \App\Validator\Siret $constraint */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value === null || $value === '' || $this->isSiret($value)) {
            return;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }
        $value = (string) $value;
        if ($this->isSiret($value)) {
            return;
        }
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->setCode(Siret::IS_SIRET_ERROR)
            ->addViolation();
    }
}
