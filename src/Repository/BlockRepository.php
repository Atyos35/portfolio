<?php

namespace App\Repository;

use App\Entity\Block;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Block>
 */
class BlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Block::class);
    }

    /**
     * @return Block[] Returns an array of block objects
     */
    public function findAllByUser($userId): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :val')
            ->setParameter('val', $userId)
            ->orderBy('b.position', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findNextPositionForBlockByUser(User $user): int
    {
        $maxPosition = $this->createQueryBuilder('b')
            ->select('MAX(b.position)')
            ->where('b.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return ((int) $maxPosition) + 1;
    }
}
