<?php

namespace App\Repository;

use App\Entity\ForumDiscussion;
use App\Entity\ForumDiscussionSearch;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
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
            ->addOrderBy('d.important', 'DESC')
            ->addOrderBy('d.date_new_com', 'DESC')
            ->getQuery();
    }


    /**
     * @return Query
    */
    public function getListDiscussionsTag($value): Query
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
            ->leftJoin('d.tags', 'tag')
            ->addSelect('tag')
            ->andWhere('tag = :val')
            ->setParameter('val', $value)
            ->addOrderBy('d.important', 'DESC')
            ->addOrderBy('d.date_new_com', 'DESC')
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
     * @return Query
     */
    public function getSearchDiscussion(ForumDiscussionSearch $search): Query
    {
        $query = $this->findVisibleQuery();

        if($search->getUsers()->count() > 0) {
            foreach ($search->getUsers() as $k => $user) {
                if($k == 0) {
                    $query = $query
                        ->andWhere(":user$k = d.auteur")
                        ->setParameter("user$k", $user);
                }
                else {
                    $query = $query
                        ->orWhere(":user$k = d.auteur")
                        ->setParameter("user$k", $user);
                }
            }
        }
        if($search->getCategories()->count() > 0) {
            foreach ($search->getCategories() as $key => $categorie) {

                if($key == 0) {
                    dump($key);
                    $query = $query
                        ->andWhere(":categorie = d.categorie")
                        ->setParameter("categorie", $categorie);
                }
                else {
                    $query = $query
                        ->orWhere(":categorie$key = d.categorie")
                        ->setParameter("categorie$key", $categorie);
                }
            }
        }
        $query = $query
            ->andWhere('d.titre like :motCle')
            ->setParameter('motCle', '%'.$search->getMotCle().'%');
        if($search->getTitre() == false) {
            $query = $query
                ->orWhere('d.message like :message')
                ->setParameter('message', '%'.$search->getMotCle().'%');
        }
        if($search->getDateCreation()) {
            $query = $query
                ->andWhere('d.date_creation > :dateCrea')
                ->setParameter('dateCrea', $search->getDateCreation());
        }

        if($search->getTrier() == 'valeur3') {
            $query = $query
                ->orderBy('d.date_creation', 'DESC');
        }
        elseif($search->getTrier() == 'valeur2') {
            $query = $query
                ->addOrderBy('d.date_new_com', 'DESC');
        }
        else {
            $query = $query
                ->addOrderBy('d.important', 'DESC')
                ->addOrderBy('d.date_new_com', 'DESC');
        }


        return $query->getQuery();
    }


    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('d');
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
