<?php

namespace App\Repository;

use App\Entity\Chatbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Chatbox|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chatbox|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chatbox[]    findAll()
 * @method Chatbox[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChatboxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chatbox::class);
    }

    // /**
    //  * @return Chatbox[] Returns an array of Chatbox objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Chatbox
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
