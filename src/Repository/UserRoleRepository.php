<?php

namespace App\Repository;

use App\Entity\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRole[]    findAll()
 * @method UserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRoleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserRole::class);
    }

    /**
    * @return UserRole[] Returns an array of UserRole objects
    */
    public function getRoles()
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'user')
            ->leftJoin('u.users', 'user')
            ->orderBy('u.level', 'ASC')
            ->getQuery()
            ->getResult();
        ;
    }

    /*
    public function findOneBySomeField($value): ?UserRole
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
