<?php

namespace App\Repository;

use App\Entity\Signalement;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    * @return Query
    */
    public function getSignalement(): Query
    {
        return $this->createQueryBuilder('s')
            ->addSelect('s', 'u')
            ->leftJoin('s.user', 'u')
            ->addSelect('u', 'r')
            ->leftJoin('u.role', 'r')
            ->addOrderBy('s.lu', 'ASC')
            ->addOrderBy('s.statut', 'ASC')
            ->addOrderBy('s.date_new_raison', 'DESC')
            ->getQuery()
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

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('s');
    }
}
