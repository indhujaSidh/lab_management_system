<?php

namespace App\Repository\User;

use App\Entity\User\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserRole>
 *
 * @method UserRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRole[]    findAll()
 * @method UserRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRole::class);
    }

    //    /**
    //     * @return UserRole[] Returns an array of UserRole objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserRole
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
