<?php

declare(strict_types=1);

namespace App\Dto\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserDto extends UserDtoAbstract
{
    #[Assert\AtLeastOneOf([
        new Assert\IsNull(),
        new Assert\Blank(),
        new Assert\PasswordStrength(['minScore' => Assert\PasswordStrength::STRENGTH_WEAK]),
    ], message: 'The password strength is too low', includeInternalMessages: false)]
    public string|null $password;

    public function getPassword(): string|null
    {
        return $this->password;
    }
}
