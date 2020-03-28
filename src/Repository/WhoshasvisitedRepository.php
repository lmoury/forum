<?php

namespace App\Repository;

use App\Entity\Whoshasvisited;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Whoshasvisited|null find($id, $lockMode = null, $lockVersion = null)
 * @method Whoshasvisited|null findOneBy(array $criteria, array $orderBy = null)
 * @method Whoshasvisited[]    findAll()
 * @method Whoshasvisited[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhoshasvisitedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Whoshasvisited::class);
    }

    /**
    * @return Whoshasvisited[] Returns an array of Whoshasvisited objects
    */
    public function getWhoshasvisited()
    {
        return $this->createQueryBuilder('w')
            ->select('w', 'v')
            ->leftJoin('w.visiteur', 'v')
            ->addSelect('v', 'r')
            ->leftJoin('v.role', 'r')
            ->orderBy('w.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function findOneBySomeField($value): ?Whoshasvisited
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.visiteur = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
