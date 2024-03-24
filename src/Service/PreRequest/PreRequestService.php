<?php

namespace App\Service\PreRequest;

use App\Entity\Appointment\PreRequests;
use Doctrine\ORM\EntityManagerInterface;

class PreRequestService implements IPreRequestService
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getPreRequestList():?array
    {
        return $this->em->getRepository(PreRequests::class)->getPreRequestList();
    }

}