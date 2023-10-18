<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Payment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 40; $i++) {
            $payment = new Payment();

            // Retrieve a random user reference using the reference name
            $randomUser = $this->getReference('user_' . $faker->numberBetween(1, 4));

            $payment->setUser($randomUser);

            if ($i < 5) {
                // label from the test's pdf (burger king example)
                $payment->setLabel('CB BK0012');

                //position gps = position from the test's pdf (burger king example)
                $payment->setGpsPosition('48.87678,57.86443');
            } else {
                // Other positions
                $wellKnownPlaces = [
                    [
                        'name' => 'McDonald\'s',
                        'lat' => 48.835529,
                        'lng' => 2.406035,
                    ],
                    [
                        'name' => 'Burger King',
                        'lat' => 48.880345,
                        'lng' => 2.357040,
                    ],
                    [
                        'name' => 'Starbucks',
                        'lat' => 48.871710,
                        'lng' => 2.342400,
                    ],
                    [
                        'name' => 'KFC',
                        'lat' => 48.880928,
                        'lng' => 2.357900,
                    ],
                    [
                        'name' => 'Quick',
                        'lat' => 48.832740,
                        'lng' => 2.352200,
                    ],
                    
                ];

                // Choose a random place for the gps_position
                $randomPlace = $wellKnownPlaces[array_rand($wellKnownPlaces)];

                // other labels
                $payment->setLabel($randomPlace['name']);
                $gps_position = $randomPlace['lat'] . ',' . $randomPlace['lng'];
                $payment->setGpsPosition($gps_position);
            }

            $payment->setAmount($faker->randomFloat(2, 1, 1000));

            $payment->setCreatedAt(new \DateTimeImmutable());
            $payment->setModifiedAt(new \DateTimeImmutable());

            $manager->persist($payment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
