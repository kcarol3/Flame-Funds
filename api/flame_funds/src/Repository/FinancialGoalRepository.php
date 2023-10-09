<?php

namespace App\Repository;

use App\Entity\IncomeCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FinancialGoal>
 *
 * @method FinancialGoal|null find($id, $lockMode = null, $lockVersion = null)
 * @method FinancialGoal|null findOneBy(array $criteria, array $orderBy = null)
 * @method FinancialGoal[]    findAll()
 * @method FinancialGoal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancialGoalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinancialGoal::class);
    }

}