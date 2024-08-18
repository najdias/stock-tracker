<?php

namespace App\Repository;

use App\Entity\StockLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<StockLog>
 */
class StockLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockLog::class);
    }

    public function findAllByUserOrderedByDate(UserInterface $user): array
    {
        return $this->createQueryBuilder('s')
            ->select(
                "DATE_FORMAT(s.date, '%Y-%m-%dT%TZ') as date",
                's.symbol',
                's.open',
                's.high',
                's.low',
                's.close'
            )
            ->andWhere('s.user = :val')
            ->setParameter('val', $user)
            ->orderBy('s.date', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }
}
