<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */

    public function upComingSessions() 
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('ucs')
            ->andwhere('ucs.dateDebut > :today')
            ->setParameter('today', $today)
            ->orderBy('ucs.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function currentSessions() 
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('cs')
            ->andwhere('cs.dateDebut <= :today')
            ->andwhere('cs.dateFin >= :today')
            ->setParameter('today', $today)
            ->orderBy('cs.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function pastSessions() 
    {
        $today = new \DateTime();

        return $this->createQueryBuilder('ps')
            ->andwhere('ps.dateFin < :today')
            ->setParameter('today', $today)
            ->orderBy('ps.dateFin', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findNonInscrits($session_id) 
    {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('s')
            ->from('App\Entity\Stagiaire', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');
        
        $sub = $em->createQueryBuilder();
        $sub->select('st')
            ->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            ->setParameter('id', $session_id)
            ->orderBy('st.nom');

        $query = $sub->getQuery();
        return $query->getResult();
    }

    public function findNonProg($session_id) 
    {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('m')
            ->from('App\Entity\Module', 'm')
            ->leftJoin('m.programmes', 'pr')
            ->where('pr.session = :id');

        $sub = $em->createQueryBuilder();
        $sub->select('mo')
            ->from('App\Entity\Module', 'mo')
            ->where($sub->expr()->notIn('mo.id', $qb->getDQL()))
            ->setParameter('id', $session_id)
            ->orderBy('mo.nom');

        $query = $sub->getQuery();
        return $query->getResult();
    }

    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
