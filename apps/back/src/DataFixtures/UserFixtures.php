<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i <= 5; $i++) {
            // Admin
            if ($i === 1) {
                $user = new User('admin@tcm.com');
                $user->setPassword($this->passwordHasher->hashPassword($user, 'admin'));
                $user->setRoles(['ROLE_ADMIN']);

                $user->setFirstName('admin fist name');
                $user->setLastName('admin last name');
            }
            // User
            else {
                $user = new User($faker->unique()->email);

                $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));

                $user->setRoles(['ROLE_USER']);

                $user->setFirstName($faker->firstName);
                $user->setLastName($faker->lastName);
            }

            $user->setScore(0);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setModifiedAt(new \DateTimeImmutable());

            $manager->persist($user);

            // Reference the user by an identifier like 'user_1', 'user_2', etc.
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}
