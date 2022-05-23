<?php

namespace App\Repository;

use App\Entity\Offres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offres[]    findAll()
 * @method Offres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offres::class);
    }
    /**
     * Recherche les offres en fonction du formulaire
     * @return void 
     */
    public function search($mots = null, $categorie = null,$pays = null,$grilleTarifaires = null){
        $query = $this->createQueryBuilder('a');
       
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.titre) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($categorie != null){
            $query->leftJoin('a.categorie', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id', $categorie);
        }

        if($pays != null){
            $query->leftJoin('a.pays', 'd');
            $query->andWhere('d.id = :id')
                ->setParameter('id', $pays);
        }
        if($grilleTarifaires != null){
            $query->leftJoin('a.grilleTarifaires', 'e');
            $query->andWhere('MATCH_AGAINST(a.grilleTarifaires.dateDebut, a.grilleTarifaires.dateFin) AGAINST (:grilleTarifaires boolean)>0')
                ->setParameter('grilleTarifaires', $grilleTarifaires);
        }
        
        return $query->getQuery()->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Offres $entity, bool $flush = true): void
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
    public function remove(Offres $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Offres[] Returns an array of Offres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offres
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
