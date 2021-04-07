<?php

namespace App\Repository;

use App\Entity\ForumDiscussionView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForumDiscussionView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumDiscussionView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumDiscussionView[]    findAll()
 * @method ForumDiscussionView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumDiscussionViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumDiscussionView::class);
    }

    // /**
    //  * @return ForumDiscussionView[] Returns an array of ForumDiscussionView objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function getDiscussionView($value1, $value2): ?ForumDiscussionView
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.user = :val')
            ->setParameter('val', $value1)
            ->andWhere('f.discussion = :val2')
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
