<?php

namespace App\Repository;

use App\Entity\Likes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Like|null find($id, $lockMode = null, $lockVersion = null)
 * @method Like|null findOneBy(array $criteria, array $orderBy = null)
 * @method Like[]    findAll()
 * @method Like[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likes::class);
    }

    /**
    * @return Like Returns an array of Like objects
    */
    public function getLikesDiscussion($discussion, $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.discussion = :discussion')
            ->setParameter('discussion', $discussion)
            ->andWhere('l.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return Like Returns an array of Like objects
    */
    public function getLikesCom($commentaire, $user)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.commentaire = :commentaire')
            ->setParameter('commentaire', $commentaire)
            ->andWhere('l.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Like
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
