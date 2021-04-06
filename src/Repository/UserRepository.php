<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
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
            ->getResult()
        ;
    }


    /**
    * @return User Returns search array user
    */
    public function getSearchListUser($value)
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
            ->andWhere('u.username LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('u.date_inscription', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return User Returns array user
    */
    public function getlistUserOnlineAndVisited($value)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->andWhere('u.date_visite > :val')
            ->setParameter('val', $value)
            ->orderBy('u.date_inscription', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return User Returns count user
    */
    public function getCountUserOnlineAndVisited($value)
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u)')
            ->andWhere('u.date_visite > :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return User Returns array user
    */
    public function getlistStaffOnline($value)
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->andWhere('u.role = 3')
            ->orWhere('u.role = 2')
            ->andWhere('u.date_visite > :val')
            ->setParameter('val', $value)
            ->orderBy('u.role', 'DESC')
            ->getQuery()
            ->getResult()
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


    /**
    * @return User Returns array user
    */
    public function getLastUsers()
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->orderBy('u.date_inscription', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
    * @return User Returns array user
    */
    public function getBirthday()
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->orderBy('u.date_inscription', 'DESC')
            ->setMaxResults(12)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
    * @return User Returns array user
    */
    public function getStaff()
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.role', 'r')
            ->addSelect('u', 'r')
            ->andWhere('u.role = 3')
            ->orWhere('u.role = 2')
            ->orderBy('r.level', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return User Returns User objects
    */
    public function getEmailUser($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function getLostPassword($id, $key)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->andWhere('u.lostPasswordKey = :key')
            ->setParameter('key', $key)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
