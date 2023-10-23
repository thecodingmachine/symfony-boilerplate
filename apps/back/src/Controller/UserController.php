<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\User\CreateUserDto;
use App\Dto\Request\User\UpdateUserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Enum\Right;
use App\Security\Voter\UserVoter;
use App\UseCase\Storage\StoreFile;
use App\UseCase\User\CreateUser;
use App\UseCase\User\DeleteUserPicture;
use App\UseCase\User\UpdateUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly CreateUser $createUser,
        private readonly UpdateUser $updateUser,
        private readonly DeleteUserPicture $deleteUserPicture,
    ) {
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function createUser(#[MapRequestPayload] CreateUserDto $userDto, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::CREATE_USER);
        $profilePicture = $request->files->get('profilePictureFile');
        if ($profilePicture !== null) {
            \assert($profilePicture instanceof UploadedFile);
            $userDto->setProfilePicture($profilePicture);
        }
        $user = $this->createUser->createUser($userDto);

        $this->entityManager->flush();

        return $this->json($user, 200, [], ["groups" => [User::GROUP_USER_DETAILS]]);
    }

    #[Route('/users', name: 'list_users', methods: ['GET'])]
    #[IsGranted(UserVoter::VIEW_ANY_USER)]
    public function listUsers(\Symfony\Bundle\SecurityBundle\Security $security): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->json($users, 200, [], ["groups" => [User::GROUP_USER_LIST]]);
    }

    #[Route('/users/{id}', name: 'get_user', methods: ['GET'])]
    #[IsGranted(UserVoter::VIEW_ANY_USER)]
    public function getUserEntity(User $user): JsonResponse
    {
        return $this->json($user, 200, [], ["groups" => [User::GROUP_USER_DETAILS]]);
    }

    #[Route('/users/{id}', name: 'update_user', methods: ['POST'])]
    #[IsGranted('ROLE_RIGHT_USER_UPDATE')]
    public function updateUser(User $user, #[MapRequestPayload] UpdateUserDto $userDto, Request $request): JsonResponse
    {
        // phpcs:disable Generic.Commenting.Fixme.TaskFound
        // FIXME: Using POST instead of PUT here because PUT does not handle multipart form data correctly
        $profilePicture = $request->files->get('profilePictureFile');
        if ($profilePicture !== null) {
            \assert($profilePicture instanceof UploadedFile);
            $userDto->setProfilePicture($profilePicture);
        }
        $user = $this->updateUser->updateUser($user, $userDto);

        $this->entityManager->flush();

        return $this->json($user, 200, [], ["groups" => User::GROUP_USER_DETAILS]);
    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    #[IsGranted(UserVoter::DELETE_ANY_USER, subject: 'user')]
    public function deleteUser(User $user): JsonResponse
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(true);
    }

    #[Route('/users/{id}/picture', name:'delete_user_picture', methods: ['DELETE'])]
    #[IsGranted(UserVoter::EDIT_ANY_USER, subject: 'user')]
    public function deleteUserPicture(User $user): JsonResponse
    {
        $this->deleteUserPicture->delete($user);

        $this->entityManager->flush();
        return new JsonResponse(true);
    }
}
