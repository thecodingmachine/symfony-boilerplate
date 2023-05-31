<?php

namespace Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // Login with a user to access the route
        $testUser = $userRepository->findOneBy(['email' => 'admin@tcm.com']);
        $client->loginUser($testUser);

        $client->request(
            'POST',
            getenv('APP_PREFIX') . '/users',
            [
                'email' => 'test1@email.com',
                'password' => 'test1password'
            ],
        );
        self::assertResponseIsSuccessful();
    }
}