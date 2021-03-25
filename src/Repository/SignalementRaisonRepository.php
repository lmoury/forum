<?php

namespace App\Repository;

use App\Entity\SignalementRaison;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SignalementRaison|null find($id, $lockMode = null, $lockVersion = null)
 * @method SignalementRaison|null findOneBy(array $criteria, array $orderBy = null)
 * @method SignalementRaison[]    findAll()
 * @method SignalementRaison[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalementRaisonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SignalementRaison::class);
    }

    /**
    * @return SignalementRaison[] Returns an array of SignalementRaison objects
    */
    public function getSignalement($value)
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.signaleur', 'u')
            ->addSelect('s', 'u')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->andWhere('s.signalement = :val')
            ->setParameter('val', $value)
            ->orderBy('s.dateSignalement', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?SignalementRaison
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
