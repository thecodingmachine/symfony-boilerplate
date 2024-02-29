<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\Request\User\CreateUserDto;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\Uuid;

/**
 * @extends ServiceEntityRepository<User>
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method array<User> findAll()
 * @method array<User> findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function createUser(CreateUserDto $userDto): User
    {
        return new User((string) Uuid::uuid4(), $userDto->getEmail(), ['ROLE_USER']);
    }

    public function createAdmin(CreateUserDto $userDto): User
    {
        return new User((string) Uuid::uuid4(), $userDto->getEmail(), ['ROLE_ADMIN']);
    }
}
