<?php

declare(strict_types=1);

namespace App\Domain\Model\Storable;

use Symfony\Component\Validator\Constraints as Assert;

final class ProfilePicture extends Storable
{
    /**
     * @Assert\Choice({"png", "jpg"}, message="user.pictures_extensions")
     */
    public function getExtension(): string
    {
        return parent::getExtension();
    }
}
