<?php

namespace App\Controller;

use App\Entity\Buchung;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BuchungRepository;

class BuchungenController extends AbstractController
{
    #[Route('/buchungen', name: 'buchungen')]
    public function index(): Response
    {
        return $this->render('main/bookings.html.twig');
    }

    #[Route('/buchung_speichern', name: 'buchung_speichern', methods: ['POST'])]
    public function speichern(Request $request, EntityManagerInterface $em,BuchungRepository $buchungRepository): Response
    {
        $datum = $request->request->get('datum');
        $startzeit = $request->request->get('startTime');
        $endzeit = $request->request->get('endTime');
        $stock = $request->request->get('stock');
        $raum = $request->request->get('raum');
        $tisch = $request->request->get('tisch');

        if ($buchungRepository->findExistingBooking(
            new \DateTime($datum),
            new \DateTime($startzeit),
            new \DateTime($endzeit),
            (int) $stock,
            (int) $raum,
            (int) $tisch
        )) {
            $this->addFlash('error', 'Dieser Tisch ist für den angegebenen Zeitraum bereits gebucht.');
            return $this->redirectToRoute('buchungen');
        }


        $buchung = new Buchung();
        $buchung->setDatum(new \DateTime($datum));
        $buchung->setStartzeit(new \DateTime($startzeit));
        $buchung->setEndzeit(new \DateTime($endzeit));
        $buchung->setStock((int) $stock);
        $buchung->setRaum((int) $raum);
        $buchung->setTisch((int) $tisch);
        $buchung->setBenutzer($this->getUser());

        $em->persist($buchung);
        $em->flush();

        $this->addFlash('success', 'Buchung erfolgreich gespeichert!');
        return $this->redirectToRoute('dashboard');
    }
}
