<?php

namespace App\Repository;

use App\Entity\ConversationReponse;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ConversationReponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConversationReponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConversationReponse[]    findAll()
 * @method ConversationReponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationReponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConversationReponse::class);
    }

    /**
    * @return Query
    */
    public function getConversationReponses($value): Query
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.conversationRep = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ConversationReponse
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
