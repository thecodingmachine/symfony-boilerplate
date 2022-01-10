<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User;

use App\Domain\Storage\ProfilePictureStorage;
use App\Infrastructure\Controller\DownloadController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TheCodingMachine\GraphQLite\Annotations\Security;

#[Route(path: '/users')]
final class UserProfilePictureController extends DownloadController
{
    private ProfilePictureStorage $profilePictureStorage;

    public function __construct(ProfilePictureStorage $profilePictureStorage)
    {
        $this->profilePictureStorage = $profilePictureStorage;
    }

    #[Route(path: '/profile-picture/{filename}', methods: ['GET'])]
    #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    public function downloadUserProfilePicture(
        string $filename
    ): Response {
        if (! $this->profilePictureStorage->fileExists($filename)) {
            throw $this->createNotFoundException();
        }

        $picture = $this->profilePictureStorage->getFileContent($filename);

        return $this->createResponseInline(
            filename   : $filename,
            fileContent: $picture
        );
    }
}
