<?php

declare(strict_types=1);

use App\Domain\Dao\ResetPasswordTokenDao;
use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\User;
use App\UseCase\User\DeleteUser;
use App\UseCase\User\ResetPassword\ResetPassword;
use TheCodingMachine\TDBM\TDBMException;

use function PHPUnit\Framework\assertCount;

beforeEach(function (): void {
    $userDao = self::$container->get(UserDao::class);
    assert($userDao instanceof UserDao);

    $merchant = new User(
        'foo',
        'bar',
        'user@foo.com',
        Locale::EN(),
        Role::USER()
    );
    $merchant->setId('1');
    $userDao->save($merchant);
});

it(
    'deletes the user',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $deleteUser = self::$container->get(DeleteUser::class);
        assert($deleteUser instanceof DeleteUser);

        $user = $userDao->getById('1');
        $deleteUser->deleteUser($user);

        $userDao->getById($user->getId());
    }
)
    ->throws(TDBMException::class)
    ->group('user');

it(
    'deletes the reset password token',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $resetPassword = self::$container->get(App\UseCase\User\ResetPassword\ResetPassword::class);
        assert($resetPassword instanceof ResetPassword);
        $deleteUser = self::$container->get(DeleteUser::class);
        assert($deleteUser instanceof DeleteUser);
        $resetPasswordTokenDao = self::$container->get(ResetPasswordTokenDao::class);
        assert($resetPasswordTokenDao instanceof ResetPasswordTokenDao);

        $user = $userDao->getById('1');
        $resetPassword->resetPassword($user->getEmail());
        assertCount(1, $resetPasswordTokenDao->findAll());

        $deleteUser->deleteUser($user);

        assertCount(0, $resetPasswordTokenDao->findAll());
        $resetPasswordTokenDao->getById($user->getId());
    }
)
    ->throws(TDBMException::class)
    ->group('user');
