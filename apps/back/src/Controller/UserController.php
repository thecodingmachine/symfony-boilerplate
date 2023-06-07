<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class UserController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly MailerInterface $mailer,
    ) {
    }

    #[Route('/user', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $userDto = new UserDto(json_decode($request->getContent(), true));

        if ($this->userRepository->findOneBy(['email' => $userDto->getEmail()])) {
            return new JsonResponse('Email already exists', 400);
        }

        $user = new User($userDto->getEmail());
        $user->setPassword($passwordHasher->hashPassword($user, $userDto->getPassword()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $email = (new TemplatedEmail())
            ->from(getenv('MAIL_HOST'))
            ->to($userDto->getEmail())
            ->subject('Registration')
            ->htmlTemplate('emails/register.html.twig');

        $this->mailer->send($email);

        return new JsonResponse($user);
    }

    #[Route('/user', name: 'list_users', methods: ['GET'])]
    public function listUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return new JsonResponse($users);
    }

    #[Route('/user/{id}', name: 'get_user', methods: ['GET'])]
    #[IsGranted('ROLE_RIGHT_USER_READ')]
    public function getUser(User $user): JsonResponse
    {
        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/user/{id}', name: 'update_user', methods: ['PUT'])]
    #[IsGranted('ROLE_RIGHT_USER_UPDATE')]
    public function updateUser(User $user, Request $request): JsonResponse
    {
        $userDto = new UserDto(json_decode($request->getContent(), true));

        if ($userDto->getEmail()) {
            $user->setEmail($userDto->getEmail());
        }

        if ($userDto->getPassword()) {
            $user->setPassword($userDto->getPassword());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
        ]);
    }

    #[Route('/user/{id}', name: 'delete_user', methods: ['DELETE'])]
    #[IsGranted('ROLE_RIGHT_USER_DELETE')]
    public function deleteUser(User $user): JsonResponse
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(true);
    }
}
