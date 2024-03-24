<?php

namespace App\Service\Appointment;

use App\Entity\Appointment\Appointment;
use Doctrine\ORM\EntityManagerInterface;

class AppointmentService implements IAppointmentService
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em,)
    {
        $this->em = $em;
    }

    public function getAppointmentListAction($patientId):?array
    {
        return $this->em->getRepository(Appointment::class)->getAppointmentForListView($patientId);
    }

}