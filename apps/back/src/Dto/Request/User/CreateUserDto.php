<?php

declare(strict_types=1);

namespace App\Dto\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDto extends UserDtoAbstract
{
    #[Assert\NotBlank]
    #[Assert\PasswordStrength(['minScore' => Assert\PasswordStrength::STRENGTH_WEAK])]
    public string $password;

    public function getPassword(): string
    {
        return $this->password;
    }
}
