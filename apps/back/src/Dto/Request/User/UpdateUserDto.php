<?php

declare(strict_types=1);

namespace App\Dto\Request\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserDto
{

    private UploadedFile|null $profilePicture = null;

    public function __construct(
        #[Assert\Email]
        private string $email,
//        #[Assert\AtLeastOneOf([
//            new Assert\IsNull(),
//            new Assert\Blank(),
////            new Assert\PasswordStrength(['minScore' => Assert\PasswordStrength::STRENGTH_WEAK]),
//        ], message: 'The password strength is too low', includeInternalMessages: false)]
        private string|null $password,
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

    public function getProfilePicture(): ?UploadedFile
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?UploadedFile $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
}
