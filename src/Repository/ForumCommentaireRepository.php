<?php

namespace App\Repository;

use App\Entity\ForumCommentaire;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumCommentaire[]    findAll()
 * @method ForumCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumCommentaire::class);
    }

    /**
    * @return Query
    */
    public function getCommentaires($value): Query
    {
        return $this->createQueryBuilder('f')
            ->select('f', 'a')
            ->leftJoin('f.auteur', 'a')
            ->addSelect('a', 'r')
            ->leftJoin('a.role', 'r')
            ->addSelect('a', 'd')
            ->leftJoin('a.forumDiscussions', 'd')
            ->addSelect('a', 'c')
            ->leftJoin('a.forumCommentaires', 'c')
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat')
            ->andWhere('f.discussion = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'DESC')
            ->getQuery()
        ;
    }

    /**
     * @return ForumCommentaire[]
     */
    public function getLastCommentaires(): array
    {
        return $this->createQueryBuilder('f')
            ->setMaxResults(5)
            ->orderBy('f.date_creation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?ForumCommentaire
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
