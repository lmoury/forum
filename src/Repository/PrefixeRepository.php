<?php

namespace App\Repository;

use App\Entity\Prefixe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Prefixe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prefixe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prefixe[]    findAll()
 * @method Prefixe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrefixeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prefixe::class);
    }

    /**
     * @return Prefixe[] Returns an array of Prefixe objects
    */
    public function getPrefixCat($value)
    {
        return $this->createQueryBuilder('p')
            ->addSelect('p', 'c')
            ->join('p.categories', 'c')
            ->andWhere('c.id = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Prefixe
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
