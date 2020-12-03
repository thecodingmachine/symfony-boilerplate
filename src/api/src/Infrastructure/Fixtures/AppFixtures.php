<?php

declare(strict_types=1);

namespace App\Infrastructure\Fixtures;

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\User;
use App\Domain\Throwable\InvalidModel;
use App\UseCase\User\DeleteUser;

final class AppFixtures extends Fixtures
{
    private UserDao $userDao;
    private DeleteUser $deleteUser;

    public function __construct(
        UserDao $userDao,
        DeleteUser $deleteUser
    ) {
        $this->userDao    = $userDao;
        $this->deleteUser = $deleteUser;

        parent::__construct();
    }

    public function purge(): void
    {
        // When deleting a user, we actually
        // also delete the companies and the
        // products (and their pictures).
        $users = $this->userDao->findAll();
        foreach ($users as $user) {
            $this->deleteUser->deleteUser($user);
        }
    }

    /**
     * @throws InvalidModel
     */
    public function load(): void
    {
        $admin = new User(
            $this->faker->firstName,
            $this->faker->lastName,
            'admin@admin.com',
            Locale::EN(),
            Role::ADMINISTRATOR()
        );
        $admin->setPassword('admin');

        $user = new User(
            $this->faker->firstName,
            $this->faker->lastName,
            'user@user.com',
            Locale::EN(),
            Role::USER()
        );
        $user->setPassword('user');

        $this->userDao->save($admin);
        $this->userDao->save($user);
    }
}
