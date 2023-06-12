<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use App\UseCase\User\CreateUser;
use App\UseCase\User\UpdateUser;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly CreateUser $createUser,
        private readonly UpdateUser $updateUser,
    ) {
    }

    #[Route('/users', name: 'create_user', methods: ['POST'])]
    public function createUser(#[MapRequestPayload] UserDto $userDto): JsonResponse
    {
        $user = $this->createUser->createUser($userDto);

        return new JsonResponse($user);
    }

    #[Route('/users', name: 'list_users', methods: ['GET'])]
    public function listUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return new JsonResponse($users);
    }

    #[Route('/users/{id}', name: 'get_user', methods: ['GET'])]
    #[IsGranted('ROLE_RIGHT_USER_READ')]
    public function getUser(User $user): JsonResponse
    {
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/users/{id}', name: 'update_user', methods: ['PUT'])]
    #[IsGranted('ROLE_RIGHT_USER_UPDATE')]
    public function updateUser(User $user, #[MapRequestPayload] UserDto $userDto): JsonResponse
    {
        $user = $this->updateUser->updateUser($user, $userDto);

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/users/{id}', name: 'delete_user', methods: ['DELETE'])]
    #[IsGranted('ROLE_RIGHT_USER_DELETE')]
    public function deleteUser(User $user): JsonResponse
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(true);
    }
}
