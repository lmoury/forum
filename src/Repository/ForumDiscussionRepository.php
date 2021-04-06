<?php

namespace App\Repository;

use App\Entity\ForumDiscussion;
use App\Entity\ForumDiscussionSearch;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method ForumDiscussion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumDiscussion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumDiscussion[]    findAll()
 * @method ForumDiscussion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumDiscussionRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, ForumDiscussion::class);
        $this->security = $security;
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
    public function getListDiscussionsPrefix($cat,  $prefix): Query
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
            ->andWhere('d.categorie = :cat')
            ->setParameter('cat', $cat)
            ->andWhere('d.prefixe = :prefix')
            ->setParameter('prefix', $prefix)
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
    public function getLastDiscussions()
    {
        $query = $this->findVisibleQuery();
        $query = $query
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat')
            ->setMaxResults(5);

        if($this->security->isGranted('ROLE_PREMIUM')) {
            $query = $query
                ->orWhere('cat.access = 4');
        }
        if($this->security->isGranted('ROLE_USER')) {
            $query = $query
                ->orWhere('cat.access = 1');
        }
        if($this->security->isGranted('ROLE_MODERATEUR')) {
            $query = $query
                ->orWhere('cat.access = 2');
        }
        if($this->security->isGranted('ROLE_ADMIN')) {
            $query = $query
                ->orWhere('cat.access = 3');
        }

        $query = $query
            ->orWhere('cat.access is null')
            ->orderBy('d.date_creation', 'DESC');
        $query = $query->getQuery();
        return $query->getResult();
    }


    /**
     * @return Query
     */
    public function getSearchDiscussion(ForumDiscussionSearch $search): Query
    {
        $query = $this->findVisibleQuery();
        $query = $query
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat')
            ->leftJoin('d.tags', 'tag')
            ->addSelect('d', 'tag');

        if($search->getCategories()->count() > 0) {
            $orModuleCateg = $query->expr()->orX();
            foreach ($search->getCategories() as $key => $categorie) {
                $orModuleCateg->add($query->expr()->eq('d.categorie', $categorie->getID()));
            }
            $query = $query
                ->andWhere($orModuleCateg);
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
        if($search->getUsers()->count() > 0) {
            $orModuleUser = $query->expr()->orX();
            foreach ($search->getUsers() as $k => $user) {
                $orModuleUser->add($query->expr()->eq('d.auteur', $user->getID()));
            }
            $query = $query
                ->andWhere($orModuleUser);
        }
        if($search->getTags()->count() > 0) {
            $orModuleTag = $query->expr()->orX();
            foreach ($search->getTags() as $key => $tag) {
                $orModuleTag->add($query->expr()->eq('tag.id', $tag->getID()));
            }
            $query = $query
                ->andWhere($orModuleTag);
        }

        $orModule = $query->expr()->orX();
        $orModule->add($query->expr()->isNull('cat.access'));
        if($this->security->isGranted('ROLE_USER')) {
            $orModule->add($query->expr()->eq('cat.access', '1'));
        }
        if($this->security->isGranted('ROLE_PREMIUM')) {
            $orModule->add($query->expr()->eq('cat.access', '4'));
        }
        if($this->security->isGranted('ROLE_MODERATEUR')) {
            $orModule->add($query->expr()->eq('cat.access', '2'));
        }
        if($this->security->isGranted('ROLE_ADMIN')) {
            $orModule->add($query->expr()->eq('cat.access', '3'));
        }
        $query = $query
            ->andWhere($orModule);

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



    /**
     * @return ForumDiscussion[]
     */
    public function getLastDiscussionsHome()
    {
        $query = $this->findVisibleQuery();
        $query = $query
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat');

        if($this->security->isGranted('ROLE_PREMIUM')) {
            $query = $query
                ->orWhere('cat.access = 4');
        }
        if($this->security->isGranted('ROLE_USER')) {
            $query = $query
                ->orWhere('cat.access = 1');
        }
        if($this->security->isGranted('ROLE_MODERATEUR')) {
            $query = $query
                ->orWhere('cat.access = 2');
        }
        if($this->security->isGranted('ROLE_ADMIN')) {
            $query = $query
                ->orWhere('cat.access = 3');
        }

        $query = $query
            ->orWhere('cat.access is null')
            ->orderBy('d.date_creation', 'DESC');
        $query = $query->getQuery();
        return $query->getResult();
    }


    /**
     * @return ForumDiscussion[]
     */
    public function getLastDiscussionsUser($value)
    {
        $query = $this->findVisibleQuery();
        $query = $query
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat');

        if($this->security->isGranted('ROLE_PREMIUM')) {
            $query = $query
                ->orWhere('cat.access = 4');
        }
        if($this->security->isGranted('ROLE_USER')) {
            $query = $query
                ->orWhere('cat.access = 1');
        }
        if($this->security->isGranted('ROLE_MODERATEUR')) {
            $query = $query
                ->orWhere('cat.access = 2');
        }
        if($this->security->isGranted('ROLE_ADMIN')) {
            $query = $query
                ->orWhere('cat.access = 3');
        }

        $query = $query
            ->orWhere('cat.access is null')
            ->andWhere('d.auteur = :val')
            ->setParameter('val', $value)
            ->orderBy('d.date_creation', 'DESC');
        $query = $query->getQuery();
        return $query->getResult();
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
}
