<?php

declare(strict_types=1);

namespace App\Dto\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

abstract class UserDtoAbstract implements PasswordDtoInterface
{
    #[Assert\Email]
    #[Assert\NotBlank]
    public string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    abstract public function getPassword(): string|null;
}
