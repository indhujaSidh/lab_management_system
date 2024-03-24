<?php

namespace App\Repository\User;

use App\Entity\User\AppUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AppUser>
 *
 * @method AppUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppUser[]    findAll()
 * @method AppUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUser::class);
    }



    public function getBackendUserListAction()
    {
        $qb = $this->createQueryBuilder('object');
        $qb->leftJoin('object.role','userRole');
        $qb->select('object')
            ->where('object.isDeleted=:isDeleted')
            ->andWhere('object.isBackendUser=:isBackend')
            ->setParameter('isDeleted',false)
            ->setParameter('isBackend',true)
        ;
        $qb->addSelect('userRole');

       return $qb->getQuery()->getArrayResult();
    }

    public function getUsersContactNumbers($para):?array
    {
        $qb = $this->createQueryBuilder('object');

        $qb->select('object.id as id')
            ->where('object.isDeleted=:isDeleted')
            ->andWhere('object.isActive=:isActive')
            ->setParameter('isDeleted',false)
            ->setParameter('isActive',true);

        if(!empty($para['input']))
        {
            $qb->andWhere('object.primaryContactNo LIKE :input')
                ->setParameter('input', '%'. $para['input'] . '%');
        }

        $qb->addSelect('object.primaryContactNo as contact');

        $qb->setMaxResults($para['limit']);

        return $qb->getQuery()->getResult();

    }
}
