<?php

namespace App\Repository\Test;

use App\Entity\Test\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Test>
 *
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test::class);
    }


    public function getTestsForListView()
    {
        $qb = $this->createQueryBuilder('object');
        $qb->leftJoin('object.category','category');

        $qb->select('object.id')
            ->where('object.isDeleted=:isDeleted')
            ->setParameter('isDeleted',false);

        $qb->addSelect('object.name as name')
            ->addSelect('object.price as price')
            ->addSelect('object.processingPeriod as processingPeriod')
            ->addSelect('category.name as categoryName')
            ->addSelect('object.isActive as isActive')
        ;

        $qb->orderBy('processingPeriod','ASC');

        return $qb->getQuery()->getArrayResult();


    }

    //    /**
    //     * @return Test[] Returns an array of Test objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Test
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
