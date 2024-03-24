<?php

namespace App\Repository\User;

use App\Entity\User\Doctor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Doctor>
 *
 * @method Doctor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Doctor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Doctor[]    findAll()
 * @method Doctor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Doctor::class);
    }

    public function getDoctorsForListView(): ?array
    {
        $qb = $this->createQueryBuilder('object');

        $qb->select('object.id as id')
            ->where('object.isDeleted =:isDeleted')
            ->setParameter('isDeleted', false);

        $qb->addSelect('object.firstName as firstName')
            ->addSelect('object.lastName as lastName')
            ->addSelect('object.specialization as specialization')
            ->addSelect('object.contactNumber as contactNumber')
            ->addSelect('object.isActive as isActive')
            ->addSelect('object.email as email');

        $qb->orderBy('object.firstName','ASC');

        return $qb->getQuery()->getResult();


    }
}
