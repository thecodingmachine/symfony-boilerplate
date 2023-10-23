<?php

namespace App\UseCase\Storage;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;

class DeleteFile
{

    public function __construct(
        private readonly FilesystemOperator $publicStorage,
        private readonly FilesystemOperator $privateStorage,
        private readonly EntityManagerInterface $entityManager,
    )
    {

    }

    public function delete(File $file) : void
    {
        $this->entityManager->remove($file);

        $storage = $file->isPrivate() ? $this->privateStorage : $this->publicStorage;
        $storage->delete($file->getStoragePath());
    }
}