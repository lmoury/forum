<?php

namespace App\Repository;

use App\Entity\UserBannir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UserBannir|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBannir|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBannir[]    findAll()
 * @method UserBannir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBannirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBannir::class);
    }

    // /**
    //  * @return UserBannir[] Returns an array of UserBannir objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function getUserBanni($value): ?UserBannir
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.banni = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
