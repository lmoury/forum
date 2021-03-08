<?php

namespace App\Repository;

use App\Entity\ForumCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @method ForumCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumCategorie[]    findAll()
 * @method ForumCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCategorieRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(RegistryInterface $registry, Security $security)
    {
        parent::__construct($registry, ForumCategorie::class);
        $this->security = $security;
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

    /**
     * @return ForumCategorie[]
     */
    public function getCategorieParent(): array
    {
        return $this->findVisibleQuery()
            ->addSelect('p.id')
            ->addSelect('p.categorie')
            ->addSelect('p.parent')
            ->addOrderBy('p.ordre', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * @return ForumCategorie[]
     */
    public function getCategorieOptions(): array
    {
        $result = [];
        $categories = $this->findVisibleQuery();
        if($this->security->isGranted('ROLE_PREMIUM')) {
            $categories = $categories
                ->orWhere('p.access = 4');
        }
        if($this->security->isGranted('ROLE_USER')) {
            $categories = $categories
                ->orWhere('p.access = 1');
        }
        if($this->security->isGranted('ROLE_MODERATEUR')) {
            $categories = $categories
                ->orWhere('p.access = 2');
        }
        if($this->security->isGranted('ROLE_ADMIN')) {
            $categories = $categories
                ->orWhere('p.access = 3');
        }

        $categories = $categories
            ->orWhere('p.access is null')
            ->andWhere('p.parent is null')
            ->addOrderBy('p.ordre', 'ASC')
            ->getQuery()
            ->getResult();

        $subCategories = $this->findVisibleQuery();
        if($this->security->isGranted('ROLE_PREMIUM')) {
            $subCategories = $subCategories
                ->orWhere('p.access = 4');
        }
        if($this->security->isGranted('ROLE_USER')) {
            $subCategories = $subCategories
                ->orWhere('p.access = 1');
        }
        if($this->security->isGranted('ROLE_MODERATEUR')) {
            $subCategories = $subCategories
                ->orWhere('p.access = 2');
        }
        if($this->security->isGranted('ROLE_ADMIN')) {
            $subCategories = $subCategories
                ->orWhere('p.access = 3');
        }

        $subCategories = $subCategories
            ->orWhere('p.access is null')
            ->andWhere('p.parent != :parentId')
            ->setParameter('parentId', 0)
            ->addOrderBy('p.ordre', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($categories as $category) {
            foreach ($subCategories as $subCategory) {
                if ($subCategory->getParent() === $category->getId()) {
                    foreach ($subCategories as $sub2Category) {
                        $result[$category->getCategorie()][$subCategory->getId()] = $subCategory;
                        if ($sub2Category->getParent() === $subCategory->getId()) {
                            $result[$sub2Category->getId()] = $sub2Category;
                        }
                    }
                }
            }
        }
        return $result;
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
