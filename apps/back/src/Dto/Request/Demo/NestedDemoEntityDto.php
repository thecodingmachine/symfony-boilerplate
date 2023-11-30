<?php

declare(strict_types=1);

namespace App\Dto\Request\Demo;

use Symfony\Component\Validator\Constraints as Assert;

class NestedDemoEntityDto
{
    #[Assert\NotBlank]
    public string $name;
}
