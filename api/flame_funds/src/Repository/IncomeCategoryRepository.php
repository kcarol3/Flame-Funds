<?php

namespace App\Repository;

use App\Entity\IncomeCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IncomeCategory>
 *
 * @method IncomeCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncomeCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncomeCategory[]    findAll()
 * @method IncomeCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomeCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncomeCategory::class);
    }

//    /**
//     * @return IncomeCategory[] Returns an array of IncomeCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?IncomeCategory
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
