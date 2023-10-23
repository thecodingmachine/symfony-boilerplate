<?php

declare(strict_types=1);

namespace App\Dto\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDto
{
    use ProfilePicture;

    public function __construct(
        #[Assert\Email]
        #[Assert\NotBlank]
        private string $email,
        #[Assert\NotBlank]
        #[Assert\PasswordStrength(['minScore' => Assert\PasswordStrength::STRENGTH_WEAK])]
        private string $password,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
