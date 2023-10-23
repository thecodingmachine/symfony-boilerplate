<?php

namespace UseCase;

use App\Dto\Request\User\CreateUserDto;
use App\Entity\User;
use App\UseCase\User\CreateUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $createUser = static::getContainer()->get(CreateUser::class);
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);

        $userDto = new CreateUserDto('test1@email.com', 'test1password');

        $userTest = new User($userDto->getEmail());
        $userTest->setPassword($passwordHasher->hashPassword($userTest, $userDto->getPassword()));

        self::assertEquals($userTest->getEmail(), $createUser->createUser($userDto)->getEmail());
        self::assertEmailCount(1);
    }
}