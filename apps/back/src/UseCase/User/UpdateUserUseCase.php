<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\Request\User\UpdateUserDto;
use App\Entity\User;

class UpdateUserUseCase
{
    public function __construct(
        private readonly PasswordUseCase $passwordUseCase,
    ) {
    }

    public function updateUser(User $user, UpdateUserDto $userDto): User
    {
        $user->setEmail($userDto->getEmail());
        $this->passwordUseCase->updatePassword($user, $userDto);

        return $user;
    }
}
