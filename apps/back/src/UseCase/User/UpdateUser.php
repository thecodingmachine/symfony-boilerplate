<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UpdateUser
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function updateUser(User $user, UserDto $userDto): User
    {
        if ($userDto->getEmail()) {
            $user->setEmail($userDto->getEmail());
        }

        if ($userDto->getPassword()) {
            $user->setPassword($userDto->getPassword());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}