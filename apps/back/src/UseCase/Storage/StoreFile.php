<?php

declare(strict_types=1);

namespace App\UseCase\Storage;

use App\Entity\Company;
use App\Entity\File;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StoreFile
{
    public const STORAGE_PREFIX_USER_PROFILE_PICTURES = 'user_profile_pictures';
    public const STORAGE_PREFIX_COMPANY_IDENTITY_FILES = 'company_identity_files';

    public function __construct(
        private readonly FilesystemOperator $publicStorage,
        private readonly FilesystemOperator $privateStorage,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function storeUploadedUserPicture(
        UploadedFile $uploadedFile,
        User $user,
    ): File {
        return $this->storeUploadedFile(
            $uploadedFile,
            false,
            $user->getProfilePicture(),
            self::STORAGE_PREFIX_USER_PROFILE_PICTURES,
        );
    }

    public function storeUploadedCompanyIdentityFile(
        UploadedFile $uploadedFile,
        Company $company,
    ): File {
        return $this->storeUploadedFile(
            $uploadedFile,
            true,
            $company->getIndentityFile(),
            self::STORAGE_PREFIX_COMPANY_IDENTITY_FILES,
        );
    }

    private function storeUploadedFile(
        UploadedFile $uploadedFile,
        bool $isPrivate,
        File|null $replaceFile = null,
        string|null $storagePrefix = null,
    ): File {
        $storage = $isPrivate ? $this->privateStorage : $this->publicStorage;
        $fileName = Uuid::uuid4()->toString() . '.' . $uploadedFile->getClientOriginalExtension();
        $filePath = ($storagePrefix ? $storagePrefix . '/' : '') . $fileName;

        $file = new File();
        if ($replaceFile !== null) {
            $file = $replaceFile;
            if ($storage->fileExists($replaceFile->getStoragePath())) {
                $storage->delete($replaceFile->getStoragePath());
            }
        }

        $file
            ->setName($uploadedFile->getClientOriginalName())
            ->setPrivate($isPrivate)
            ->setStoragePath($filePath);

        $this->entityManager->persist($file);
        $storage->write($filePath, $uploadedFile->getContent());

        return $file;
    }
}
