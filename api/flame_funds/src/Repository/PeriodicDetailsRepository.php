<?php

namespace App\Repository;

use App\Entity\PeriodicDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PeriodicDetails>
 *
 * @method PeriodicDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method PeriodicDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method PeriodicDetails[]    findAll()
 * @method PeriodicDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PeriodicDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PeriodicDetails::class);
    }

//    /**
//     * @return PeriodicDetails[] Returns an array of PeriodicDetails objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PeriodicDetails
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
