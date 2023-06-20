<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserDto
{
    public function __construct(
        #[Assert\Email]
        private readonly string $email,
        private readonly string|null $password,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string|null
    {
        return $this->password;
    }
}
