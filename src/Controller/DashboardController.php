<?php

namespace App\Controller;

use App\Repository\BuchungRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(BuchungRepository $buchungRepository): Response
    {
        $buchungen = $buchungRepository->findBy(['benutzer' => $this->getUser()]);

        $firstDayOfThisMonth = new \DateTime('first day of this month');
        $lastDayOfThisMonth = new \DateTime('last day of this month'); //Buchungen für den aktuellen Monat
        $buchungenDieserMonat = $buchungRepository->createQueryBuilder('b')
            ->where('b.benutzer = :benutzer')
            ->andWhere('b.datum BETWEEN :firstDayOfThisMonth AND :lastDayOfThisMonth')
            ->setParameter('benutzer', $this->getUser())
            ->setParameter('firstDayOfThisMonth', $firstDayOfThisMonth)
            ->setParameter('lastDayOfThisMonth', $lastDayOfThisMonth)
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/dashboard.html.twig', [
            'buchungen' => $buchungen,
            'anzahlBuchungenDieserMonat' => count($buchungenDieserMonat)
        ]);
    }

}