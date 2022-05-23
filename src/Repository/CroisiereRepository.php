<?php

namespace App\Repository;

use App\Entity\Croisiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Croisiere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Croisiere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Croisiere[]    findAll()
 * @method Croisiere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CroisiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Croisiere::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Croisiere $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Croisiere $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

 /**
     * @return Croisiere[] Returns an array of Croisiere objects
    */
  
    public function findByVoyagePay($value)
    {
        return $this->createQueryBuilder('v')
            ->join('v.pays' ,'p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
   

    /*
    public function findOneBySomeField($value): ?Croisiere
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
