<?php

namespace App\Service\User;

use App\Entity\User\Doctor;
use Doctrine\ORM\EntityManagerInterface;

class DoctorService implements IDoctorService
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em,)
    {
        $this->em = $em;
    }

    public function getDoctorsForList():?array
    {
        return $this->em->getRepository(Doctor::class)->getDoctorsForListView();

    }

}