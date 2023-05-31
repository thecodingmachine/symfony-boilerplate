<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\UserDto;
use App\Entity\User;
use App\Mailer\UserMailer;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUser
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserMailer $userMailer,
    ) {
    }

    public function createUser(UserDto $userDto): User
    {
        if ($this->userRepository->findOneBy(['email' => $userDto->getEmail()])) {
            throw new BadRequestHttpException('Email already exists');
        }

        $user = new User($userDto->getEmail());
        $user->setPassword($this->passwordHasher->hashPassword($user, $userDto->getPassword()));
        $this->entityManager->persist($user);

        $this->userMailer->sendRegistrationMail($user);

        return $user;
    }
}
