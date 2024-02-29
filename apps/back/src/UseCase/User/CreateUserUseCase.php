<?php

declare(strict_types=1);

namespace App\UseCase\User;

use App\Dto\Request\User\CreateUserDto;
use App\Entity\User;
use App\Exception\User\UnexpectedNotUpdatedPassword;
use App\Mailer\UserMailer;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateUserUseCase
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserMailer $userMailer,
        private readonly PasswordUseCase $passwordUseCase,
    ) {
    }

    public function createUser(CreateUserDto $userDto): User
    {
        if ($this->userRepository->findOneBy(['email' => $userDto->getEmail()])) {
            throw new BadRequestHttpException('Email already exists');
        }

        $user = $this->userRepository->createUser($userDto);
        if (!$this->passwordUseCase->updatePassword($user, $userDto)) {
            throw new UnexpectedNotUpdatedPassword();
        }
        $this->entityManager->persist($user);

        $this->userMailer->sendRegistrationMail($user);

        return $user;
    }
}
