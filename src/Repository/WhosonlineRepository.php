<?php

namespace App\Repository;

use App\Entity\Whosonline;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Whosonline|null find($id, $lockMode = null, $lockVersion = null)
 * @method Whosonline|null findOneBy(array $criteria, array $orderBy = null)
 * @method Whosonline[]    findAll()
 * @method Whosonline[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WhosonlineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Whosonline::class);
    }

    /**
    * @return Whosonline[] Returns an array of Whosonline objects
    */
    public function getWhosonline()
    {
        return $this->createQueryBuilder('w')
            ->select('w', 'o')
            ->leftJoin('w.online', 'o')
            ->addSelect('o', 'r')
            ->leftJoin('o.role', 'r')
            ->orderBy('w.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    public function getWhosonlineUser($value): ?Whosonline
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.online = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function getWhosonlineIp($value): ?Whosonline
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.onlineIp = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
