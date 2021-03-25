<?php

namespace App\Repository;

use App\Entity\Signalement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Signalement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signalement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signalement[]    findAll()
 * @method Signalement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signalement::class);
    }

    /**
    * @return Signalement[] Returns an array of Signalement objects
    */
    public function getSignalement($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function getSignalChatbox($value): ?Signalement
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idSignal = :val')
            ->setParameter('val', $value)
            ->andWhere('s.type = 3')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getSignalDiscu($value): ?Signalement
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idSignal = :val')
            ->setParameter('val', $value)
            ->andWhere('s.type = 1')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getSignalCom($value): ?Signalement
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idSignal = :val')
            ->setParameter('val', $value)
            ->andWhere('s.type = 2')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
