<?php

namespace App\Service\Appointment;

use App\Entity\Appointment\AppointmentTestMappings;
use Doctrine\ORM\EntityManagerInterface;

class AppointmentMappingService implements IAppointmentMappingService
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em,)
    {
        $this->em = $em;
    }

    public function getAppointmentTestResults($appointmentId):?array
    {
        return $this->em->getRepository(AppointmentTestMappings::class)->getAppointmentTestResults($appointmentId);
    }

}