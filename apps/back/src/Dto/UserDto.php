<?php

namespace App\Dto;

class UserDto
{
    private string|null $email;
    private string|null $password;

    public function __construct(array $data)
    {
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}