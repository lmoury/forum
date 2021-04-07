<?php

namespace App\Repository;

use App\Entity\Chatbox;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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


    /**
     * @return Chatbox[] Returns an array of Chatbox objects
     */
    public function getChatbox()
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'u')
            ->leftJoin('c.User', 'u')
            ->addSelect('u', 'r')
            ->leftJoin('u.role', 'r')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Chatbox[] Returns an array of Chatbox objects
     */
    public function getNewMessagesChatbox($value)
    {
        return $this->createQueryBuilder('c')
            ->select('c', 'u')
            ->leftJoin('c.User', 'u')
            ->addSelect('u', 'r')
            ->leftJoin('u.role', 'r')
            ->andWhere('c.id > :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

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
