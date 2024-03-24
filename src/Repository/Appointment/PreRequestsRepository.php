<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\PreRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PreRequests>
 *
 * @method PreRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method PreRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method PreRequests[]    findAll()
 * @method PreRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PreRequestsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreRequests::class);
    }

    public function getPreRequestList()
    {
        $qb = $this->createQueryBuilder('object');
        $qb->leftJoin('object.doctor','doctor');
        $qb->leftJoin('object.processState','state');

        $qb->select('object.id as id')
            ->where('object.isDeleted=:isDeleted')
            ->setParameter('isDeleted',false);

        $qb->addSelect('object.contactNo as contactNo')
            ->addSelect('object.firstName as firstName')
            ->addSelect('object.preferredDate as preferredDate')
//            ->addSelect("DATE_FORMAT(object.preferredDate, '%Y-%m-%d %H:%i:%s') as preferredDate")
            ->addSelect('doctor.firstName as doctorName')
            ->addSelect('state.name as stateName')
            ->addSelect('object.testRequisitionFilename as fileName')
        ;

        return $qb->getQuery()->getArrayResult();

    }

    //    /**
    //     * @return PreRequests[] Returns an array of PreRequests objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PreRequests
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
