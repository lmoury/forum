<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
    * @return User Returns User objects
    */
    public function getLastUser()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return User Returns array user
    */
    public function getlistUser()
    {
        return $this->createQueryBuilder('u')

            ->select('u', 'd')
            ->leftJoin('u.forumDiscussions', 'd')
            ->leftJoin('u.forumCommentaires', 'c')
            ->addSelect('u', 'c')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat')
            ->orderBy('u.date_inscription', 'DESC')
            ->getQuery()
            ->getResult();
        ;
    }

    /**
    * @return User Returns user
    */
    public function getUser($value)
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'd')
            ->leftJoin('u.forumDiscussions', 'd')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->leftJoin('u.forumCommentaires', 'c')
            ->addSelect('u', 'c')
            ->leftJoin('d.categorie', 'cat')
            ->addSelect('d', 'cat')
            ->leftJoin('c.discussion', 'disc')
            ->addSelect('c', 'disc')
            ->leftJoin('disc.auteur', 'aut')
            ->addSelect('disc', 'aut')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
