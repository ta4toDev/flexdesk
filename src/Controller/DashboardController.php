<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(BookingRepository $bookingRepository): Response
    {
        $bookings = $bookingRepository->findBy(['user' => $this->getUser()]);

        $firstDayOfThisMonth = new \DateTime('first day of this month');
        $lastDayOfThisMonth = new \DateTime('last day of this month'); 
        $bookingsThisMonth = $bookingRepository->createQueryBuilder('b')
            ->where('b.user = :user')
            ->andWhere('b.date BETWEEN :firstDayOfThisMonth AND :lastDayOfThisMonth')
            ->setParameter('user', $this->getUser())
            ->setParameter('firstDayOfThisMonth', $firstDayOfThisMonth)
            ->setParameter('lastDayOfThisMonth', $lastDayOfThisMonth)
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/dashboard.html.twig', [
            'bookings' => $bookings,
            'CountOfBookingsThisMonth' => count($bookingsThisMonth)
        ]);
    }

}