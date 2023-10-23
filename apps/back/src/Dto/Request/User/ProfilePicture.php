<?php

declare(strict_types=1);

namespace App\Dto\Request\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ProfilePicture
{
    private UploadedFile|null $profilePicture = null;

    public function getProfilePicture(): UploadedFile|null
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(UploadedFile|null $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
}
