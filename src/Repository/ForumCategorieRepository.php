<?php

namespace App\Repository;

use App\Entity\ForumCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ForumCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumCategorie[]    findAll()
 * @method ForumCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCategorieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ForumCategorie::class);
    }

    /**
     * @return ForumCategorie[]
     */
    public function getListCateg(): array
    {
        return $this->findVisibleQuery()
            ->addSelect('p', 'd')
            ->leftJoin('p.forumDiscussions', 'd')
            ->leftJoin('d.auteur', 'a')
            ->addSelect('d', 'a')
            ->leftJoin('d.forumCommentaires', 'c')
            ->addSelect('d', 'c')
            ->leftJoin('c.auteur', 'aut')
            ->addSelect('c', 'aut')
            ->addOrderBy('p.ordre', 'ASC')
            ->addOrderBy('d.date_new_com', 'DESC')
            ->getQuery()
            ->getResult();
    }

    private function findVisibleQuery()
    {
        return $this->createQueryBuilder('p');
    }

    // /**
    //  * @return ForumCategorie[] Returns an array of ForumCategorie objects
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
    public function findOneBySomeField($value): ?ForumCategorie
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
