<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**  This route demonstrate the usage of role_right */
    #[Route('/user', name: 'user_put', methods: ['PUT'])]
    #[IsGranted('ROLE_RIGHT_USER_CREATE')]
    public function login(Request $request): JsonResponse
    {
        $data = \json_decode((string) $request->getContent(), true, 512);
        if (!$data) {
            throw new BadRequestException('data is not valid');
        }

        if (!\is_string($data['username'])) {
            throw new BadRequestException('username must be a string');
        }
        $username = $data['username'];

        $user = $this->userRepository->createUser($username);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse($user);
    }

    /** This route demonstrate the usage of role_right */
    #[Route('/profile', name: 'profile_get', methods: ['GET'])]
    #[IsGranted('ROLE_RIGHT_ACCESS')]
    public function profile(#[CurrentUser] User $user): JsonResponse
    {
        return new JsonResponse($user);
    }
}
