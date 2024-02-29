<?php

declare(strict_types=1);

namespace App\Dto\Request\User;

interface PasswordDtoInterface
{
    public function getPassword(): string|null;
}
