<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\Request\CreateUserDto;
use App\Dto\Request\UpdateUserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\Enum\Right;
use App\Security\Voter\UserVoter;
use App\UseCase\User\CreateUser;
use App\UseCase\User\UpdateUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    ) {
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function createUser(#[MapRequestPayload] CreateUserDto $userDto): JsonResponse
    {
        $this->denyAccessUnlessGranted(UserVoter::CREATE_USER);
        $user = $this->createUser->createUser($userDto);

        $this->entityManager->flush();

        return new JsonResponse($user);
    }

    #[Route('/users', name: 'list_users', methods: ['GET'])]
    #[IsGranted(UserVoter::VIEW_ANY_USER)]
    public function listUsers(\Symfony\Bundle\SecurityBundle\Security $security): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return new JsonResponse($users);
    }

    #[Route('/users/{id}', name: 'get_user', methods: ['GET'])]
    #[IsGranted(UserVoter::VIEW_ANY_USER)]
    public function getUserEntity(User $user): JsonResponse
    {
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/users/{id}', name: 'update_user', methods: ['PUT'])]
    #[IsGranted(UserVoter::EDIT_ANY_USER, subject: 'user')]
    public function updateUser(User $user, #[MapRequestPayload] UpdateUserDto $userDto): JsonResponse
    {
        $user = $this->updateUser->updateUser($user, $userDto);

        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    #[IsGranted(UserVoter::DELETE_ANY_USER, subject: 'user')]
    public function deleteUser(User $user): JsonResponse
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(true);
    }
}
