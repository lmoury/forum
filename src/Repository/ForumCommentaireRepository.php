<?php

namespace App\Repository;

use App\Entity\ForumCommentaire;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method ForumCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumCommentaire[]    findAll()
 * @method ForumCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumCommentaireRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, ForumCommentaire::class);
        $this->security = $security;
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

            $query = $this->findVisibleQuery();
            $query = $query
                ->leftJoin('f.discussion', 'd')
                ->addSelect('f', 'd')
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
                ->orderBy('f.date_creation', 'DESC');
            $query = $query->getQuery();
            return $query->getResult();
    }

    private function findVisibleQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('f');
    }


    /**
     * @return ForumCommentaire[]
     */
    public function getLastCommentairesUser($value): array
    {

            $query = $this->findVisibleQuery();
            $query = $query
                ->leftJoin('f.discussion', 'd')
                ->addSelect('f', 'd')
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
                ->andWhere('f.auteur = :val')
                ->setParameter('val', $value)
                ->orderBy('f.date_creation', 'DESC');
            $query = $query->getQuery();
            return $query->getResult();
    }


    public function getCommentaire($value): ?ForumCommentaire
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
