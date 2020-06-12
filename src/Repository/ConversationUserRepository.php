<?php

namespace App\Repository;

use App\Entity\ConversationUser;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConversationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversationUser[]    findAll()
 * @method ConversationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConversationUser::class);
    }


    /**
    * @return Query
    */
    public function getList($participant): Query
    {
        return $this->createQueryBuilder('c')
            ->addSelect('c', 't')
            ->join('c.conversation', 't')
            ->andWhere('c.participant = :val')
            ->setParameter('val', $participant)
            ->addOrderBy('c.lu', 'ASC')
            ->addOrderBy('c.important', 'DESC')
            ->addOrderBy('t.created_at', 'DESC')
            ->getQuery()
        ;
    }


    /**
    * @return ConversationUser[] Returns an array of ConversationUser objects
    */
    public function getListConversationUser($conversation)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.conversation = :val')
            ->setParameter('val', $conversation)
            ->getQuery()
            ->getResult()
        ;
    }


    public function getConversationUser($conversation, $participant): ?ConversationUser
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.conversation = :con')
            ->setParameter('con', $conversation)
            ->andWhere('c.participant = :part')
            ->setParameter('part', $participant)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
