<?php

namespace App\Repository;

use App\Entity\Buchung;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Buchung>
 */
class BuchungRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Buchung::class);
    }
    /**
     * Prüft, ob eine Buchung für den angegebenen Tisch, Datum und Zeitraum existiert.
     *
     * @param \DateTimeInterface $datum
     * @param \DateTimeInterface $startzeit
     * @param \DateTimeInterface $endzeit
     * @param int $stock
     * @param int $raum
     * @param int $tisch
     * @return Buchung|null
     */
    public function findExistingBooking(
        \DateTimeInterface $datum,
        \DateTimeInterface $startzeit,
        \DateTimeInterface $endzeit,
        int $stock,
        int $raum,
        int $tisch
    ): ?Buchung {
        return $this->createQueryBuilder('b')
            ->where('b.datum = :datum')
            ->andWhere('b.stock = :stock')
            ->andWhere('b.raum = :raum')
            ->andWhere('b.tisch = :tisch')
            ->andWhere('(:startzeit < b.endzeit AND :endzeit > b.startzeit)')
            ->setParameter('datum', $datum)
            ->setParameter('startzeit', $startzeit)
            ->setParameter('endzeit', $endzeit)
            ->setParameter('stock', $stock)
            ->setParameter('raum', $raum)
            ->setParameter('tisch', $tisch)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Buchung[] Returns an array of Buchung objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Buchung
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
