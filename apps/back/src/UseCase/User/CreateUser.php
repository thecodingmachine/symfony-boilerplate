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

class CreateUser
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly MailerInterface $mailer,
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
        $this->entityManager->flush();

        $email = (new TemplatedEmail())
            ->from(getenv('MAIL_HOST'))
            ->to($userDto->getEmail())
            ->subject('Registration')
            ->htmlTemplate('emails/register.html.twig');

        $this->mailer->send($email);

        return $user;
    }
}
