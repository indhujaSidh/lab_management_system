<?php

namespace App\Repository\Appointment;

use App\Entity\Appointment\AppointmentTestMappings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppointmentTestMappings>
 *
 * @method AppointmentTestMappings|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppointmentTestMappings|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppointmentTestMappings[]    findAll()
 * @method AppointmentTestMappings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppointmentTestMappingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppointmentTestMappings::class);
    }


    public function getAppointmentTestResults($appointmentId)
    {
        $qb = $this->createQueryBuilder('object');
        $qb->leftJoin('object.AppointmentId','appointment');
        $qb->leftJoin('appointment.patientId','patient');
        $qb->leftJoin('object.test','test');

        $qb->select('object.id as id');

        if(!empty($appointmentId))
        {
            $qb->where('appointment.id=:appointmentId')
                ->setParameter('appointmentId',$appointmentId);
        }

        $qb->addSelect('appointment.refNo as refNo')
            ->addSelect('test.name as testName')
            ->addSelect('object.printedDate as printedDate')
            ->addSelect('object.reportFile as reportFile')
            ->addSelect('patient.contactNumber as contactNo')
            ->addSelect('patient.firstName as firstName')
            ->addSelect('patient.lastName as lastName')

        ;

        return $qb->getQuery()->getArrayResult();
    }
}
