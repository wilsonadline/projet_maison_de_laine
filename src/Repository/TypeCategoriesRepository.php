<?php

namespace App\Repository;

use App\Entity\TypeCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeCategories[]    findAll()
 * @method TypeCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeCategories::class);
    }

    // /**
    //  * @return TypeCategories[] Returns an array of TypeCategories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeCategories
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}