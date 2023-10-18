<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function createUser(string $identitier): User
    {
        return new User($identitier, ['ROLE_USER']);
    }

    public function createAdmin(string $identitier): User
    {
        return new User($identitier, ['ROLE_ADMIN']);
    }

    /**
     * Counts the number of identifications made by a user which have an associated Place.
     */
    public function countIdentifications(User $user): int
    {
        $qb = $this->createQueryBuilder('u');

        // Use a join to count the number of payments associated with the user
        // that also have a Place associated with them.
        return $qb->select('count(p.id)')
                  ->join('u.payments', 'p')
                  ->where('u.id = :userId')
                  ->andWhere('p.place IS NOT NULL')  // Only count payments with a Place
                  ->setParameter('userId', $user->getId())
                  ->getQuery()
                  ->getSingleScalarResult();
    }

    /**
     * Counts the number of identifications made by a user which do not have an associated Place.
     */
    public function countIdentificationsWithoutPlace(User $user): int
    {
        $qb = $this->createQueryBuilder('u');

        // Use a join to count the number of payments associated with the user
        // that do not have a Place associated with them.
        return $qb->select('count(p.id)')
                ->join('u.payments', 'p')
                ->where('u.id = :userId')
                ->andWhere('p.place IS NULL')  // Only count payments without a Place
                ->setParameter('userId', $user->getId())
                ->getQuery()
                ->getSingleScalarResult();
    }
}
