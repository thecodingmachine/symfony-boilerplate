<?php

declare(strict_types=1);

namespace App\Dto\Request\Demo;

use App\Validator\Siret;
use Symfony\Component\Validator\Constraints as Assert;

class CreateDemoValidationDto
{
    #[Assert\Type(type: NestedDemoEntityDto::class)]
    #[Assert\Valid]
    public NestedDemoEntityDto|null $nestedDemoEntity;

    /**
     * this shall be changed to datetime object once SF issue is resolved https://github.com/symfony/symfony/issues/53815#issuecomment-1973644342
    */
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\DateTime(format: 'Y-m-d\TH:i:s.u\Z')]
    public string|null $startDate;
    #[Siret]
    #[Assert\NotBlank]
    public string $siret;
    #[Assert\NotBlank]
    public string $textField;
/** @var array<NestedDemoEntityDto> */
    #[Assert\Valid]
    #[Assert\NotNull]
    public array $nestedDemoEntityList;

    /** remove once https://github.com/symfony/symfony/issues/53815#issuecomment-1973644342 is resolved **/
    public function getStartDateAsObject(): \DateTimeInterface
    {
        // This shall not happen due to asserts
        if (!$this->startDate) {
            throw new \Exception();
        }

        return new \DateTimeImmutable($this->startDate);
    }
}
