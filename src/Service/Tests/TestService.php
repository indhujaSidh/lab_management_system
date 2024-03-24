<?php

namespace App\Service\Tests;

use App\Entity\Test\Test;
use Doctrine\ORM\EntityManagerInterface;

class TestService implements ITestService
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getTestsForListView():?array
    {
        return $this->em->getRepository(Test::class)->getTestsForListView();
    }

}