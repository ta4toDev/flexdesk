<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }
    /**
     * Find an existing booking for the given date, time, floor, room, and table.
     *
     * @param \DateTimeInterface $date
     * @param \DateTimeInterface $startTime
     * @param \DateTimeInterface $endTime
     * @param int $floor
     * @param int $room
     * @param int $desk
     * @return Booking|null
     */
    public function findExistingBooking(
        \DateTimeInterface $date,
        \DateTimeInterface $startTime,
        \DateTimeInterface $endTime,
        int $floor,
        int $room,
        int $desk
    ): ?Booking {
        return $this->createQueryBuilder('b')
            ->where('b.date = :date')
            ->andWhere('b.floor = :floor')
            ->andWhere('b.room = :room')
            ->andWhere('b.desk = :desk')
            ->andWhere('(b.startTime BETWEEN :startTime AND :endTime OR b.endTime BETWEEN :startTime AND :endTime)')
            ->setParameter('date', $date)
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime)
            ->setParameter('floor', $floor)
            ->setParameter('room', $room)
            ->setParameter('desk', $desk)
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
