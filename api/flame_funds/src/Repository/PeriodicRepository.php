<?php

namespace App\Repository;

use App\Entity\Periodic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Periodic>
 *
 * @method Periodic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Periodic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Periodic[]    findAll()
 * @method Periodic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Periodic::class);
    }

}