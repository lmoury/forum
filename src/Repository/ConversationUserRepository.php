<?php

namespace App\Repository;

use App\Entity\ConversationUser;
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

    // /**
    //  * @return ConversationUser[] Returns an array of ConversationUser objects
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
