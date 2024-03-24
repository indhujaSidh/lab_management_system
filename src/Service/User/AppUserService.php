<?php

namespace App\Service\User;

use App\Entity\User\AppUser;
use Doctrine\ORM\EntityManagerInterface;

class AppUserService implements IAppUserService
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em,)
    {
        $this->em = $em;
    }

    public function getBackendUserListAction():?array
    {
        return $this->em->getRepository(AppUser::class)->getBackendUserListAction();

    }

    public function getUsersContactNumbers($para):?array
    {
        return $this->em->getRepository(AppUser::class)->getUsersContactNumbers($para);
    }

}