<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Payment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method array<Payment>    findAll()
 * @method array<Payment>    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function countPaymentsByLabelExcludingUser(string $label): int
    {
        $qb = $this->createQueryBuilder('p');

        return $qb->select('count(p.id)')
                  ->where('p.label = :label')
                  ->andWhere('p.place IS NOT NULL')
                  ->setParameter('label', $label)
                  ->getQuery()
                  ->getSingleScalarResult();
    }

    public function countUnvalidatedPaymentsForUser(User $user): int
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.user = :user')
            ->andWhere('p.place IS NULL')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
