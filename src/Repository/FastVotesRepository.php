<?php

namespace App\Repository;

use App\Entity\FastVotes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FastVotes|null find($id, $lockMode = null, $lockVersion = null)
 * @method FastVotes|null findOneBy(array $criteria, array $orderBy = null)
 * @method FastVotes[]    findAll()
 * @method FastVotes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FastVotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FastVotes::class);
    }

    // /**
    //  * @return FastVotes[] Returns an array of FastVotes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FastVotes
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
