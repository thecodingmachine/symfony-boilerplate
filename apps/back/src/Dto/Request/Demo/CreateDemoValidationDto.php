<?php

declare(strict_types=1);

namespace App\Dto\Request\Demo;

use App\Validator\Siret;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDemoValidationDto
{
    #[Assert\Type(type: NestedDemoEntityDto::class), Assert\Valid]
    public NestedDemoEntityDto|null $nestedDemoEntity;
    #[Assert\NotNull]
    public \DateTimeInterface $startDate;
    #[Siret]
    #[Assert\NotBlank]
    public string $siret;
    #[Assert\NotBlank]
    public string $textField;
/** @var array<NestedDemoEntityDto> */
    #[Assert\Valid]
    #[Assert\NotNull]
    public array $nestedDemoEntityList;
}
