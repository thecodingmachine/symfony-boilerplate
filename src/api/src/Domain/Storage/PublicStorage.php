<?php

declare(strict_types=1);

namespace App\Domain\Storage;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class PublicStorage extends Storage
{
    public function __construct(
        ParameterBagInterface $parameters,
        ValidatorInterface $validator,
        FilesystemOperator $publicStorage
    ) {
        parent::__construct($parameters, $validator, $publicStorage);
    }
}
