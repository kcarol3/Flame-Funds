<?php

namespace App\Repository;

use App\Entity\GoogleSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GoogleSheet>
 *
 * @method GoogleSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoogleSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoogleSheet[]    findAll()
 * @method GoogleSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoogleSheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoogleSheet::class);
    }

//    /**
//     * @return GoogleSheet[] Returns an array of GoogleSheet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GoogleSheet
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
