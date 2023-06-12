<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // TODO implement fixtures
        $user = new User('admin@tcm.com', []);
        $manager->persist($user);

        $manager->flush();
    }
}
