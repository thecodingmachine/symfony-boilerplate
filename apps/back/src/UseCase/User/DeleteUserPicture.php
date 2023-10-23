<?php

namespace App\UseCase\User;

use App\Entity\User;
use App\Repository\FileRepository;
use App\Repository\UserRepository;
use App\UseCase\Storage\DeleteFile;
use Doctrine\ORM\EntityManagerInterface;

class DeleteUserPicture
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DeleteFile $deleteFile
    )
    {
    }

    public function delete(User $user): void
    {
        $this->entityManager->beginTransaction();

        $profilePicture = $user->getProfilePicture();
        if (null !== $profilePicture){
            $user->setProfilePicture(null);
            $this->deleteFile->delete($profilePicture);
        }

        $this->entityManager->commit();
    }

}
