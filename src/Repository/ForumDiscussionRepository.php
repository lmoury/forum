<?php

namespace App\Repository;

use App\Entity\ForumDiscussion;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumDiscussion[]    findAll()
 * @method ForumDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumDiscussionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumDiscussion::class);
    }

    /**
     * @return Query
    */
    public function getListDiscussions($value): Query
    {
        return $this->createQueryBuilder('d')
            ->addSelect('d', 'c')
            ->leftJoin('d.forumCommentaires', 'c')
            ->leftJoin('c.auteur', 'ca')
            ->addSelect('c', 'ca')
            ->leftJoin('d.auteur', 'a')
            ->addSelect('d', 'a')
            ->addSelect('a', 'r')
            ->leftJoin('a.role', 'r')
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat')
            ->andWhere('d.categorie = :val')
            ->setParameter('val', $value)
            ->orderBy('d.date_new_com', 'DESC')
            ->getQuery();
    }

    /**
     * @return ForumDiscussion[]
     */
    public function getLastDiscussions(): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.categorie', 'cat')
            ->addSelect('f', 'cat')
            ->setMaxResults(5)
            ->orderBy('f.date_creation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return ForumDiscussion[]
     */
    public function getSearchDiscussion($value): array
    {
        return $this->createQueryBuilder('d')
            ->addSelect('d', 'c')
            ->leftJoin('d.forumCommentaires', 'c')
            ->leftJoin('c.auteur', 'ca')
            ->addSelect('c', 'ca')
            ->leftJoin('d.auteur', 'a')
            ->addSelect('d', 'a')
            ->addSelect('a', 'r')
            ->leftJoin('a.role', 'r')
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat')
            ->andWhere('d.categorie = :val')
            ->setParameter('d.titre', '%'.$value.'%')
            ->setParameter('val', $value)
            ->orderBy('d.date_new_com', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ForumDiscussion[] Returns an array of ForumDiscussion objects
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

    /*
    public function findOneBySomeField($value): ?ForumDiscussion
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
