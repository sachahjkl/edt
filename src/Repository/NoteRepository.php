<?php

namespace App\Repository;

use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function getMoyenne(Eleve $eleve, Matiere $matiere)
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select("SUM(n.note * e.coefficient)/SUM(e.coefficient)")
            ->from("App:Note", "n")
            ->innerJoin("n.examen", "e")
            ->where("e.matiere = :matiere")
            ->andWhere("n.eleve = :eleve")
            ->setParameter("matiere", $matiere)
            ->setParameter("eleve", $eleve);

        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Note[] Returns an array of Note objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Note
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
