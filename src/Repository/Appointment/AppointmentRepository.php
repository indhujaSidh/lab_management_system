<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 *
 * @method Appointment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appointment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appointment[]    findAll()
 * @method Appointment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }


    public function getAppointmentForListView($patientId): ?array
    {
        $qb = $this->createQueryBuilder('object');
        $qb->leftJoin('object.patientId', 'patient');
        $qb->leftJoin('object.paymentStatus', 'status');
        $qb->leftJoin('object.doctorId', 'doctor');
        $qb->leftJoin('object.timeSlot','timeSlot');
        $qb->select('object.id as id')
            ->where('object.isActive=:isActive')
            ->andWhere('object.isDeleted=:isDeleted')
            ->setParameter('isActive',true)
            ->setParameter('isDeleted',false)
        ;

        if(!empty($patientId))
        {
            $qb->andWhere('patient.id=:patientId')
                ->setParameter('patientId',$patientId);
        }
        $qb->addSelect('patient.firstName as firstName')
            ->addSelect('object.refNo as refNo')
            ->addSelect('patient.contactNumber as contactNo')
            ->addSelect('status.name as paymentStatus')
            ->addSelect('doctor.firstName as doctorName')
            ->addSelect('object.refDoctor as refDoctor')
            ->addSelect('status.metaCode as paymentMeta')
            ->addSelect('timeSlot.name as timeSlotName')
            ->addSelect('object.amount as amount');

        return $qb->getQuery()->getArrayResult();
    }
}
