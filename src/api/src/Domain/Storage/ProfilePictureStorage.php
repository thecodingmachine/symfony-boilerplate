<?php

declare(strict_types=1);

namespace App\Domain\Storage;

final class ProfilePictureStorage extends PrivateStorage
{
    protected function getDirectoryName(): string
    {
        return 'profile_picture';
    }
}
