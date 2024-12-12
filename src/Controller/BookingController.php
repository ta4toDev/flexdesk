<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookingRepository;

class BookingController extends AbstractController
{
    #[Route('/bookings', name: 'bookings')]
    public function index(): Response
    {
        return $this->render('main/bookings.html.twig');
    }

    #[Route('/save-booking', name: 'save_booking', methods: ['POST'])]
    public function speichern(Request $request, EntityManagerInterface $em, BookingRepository $bookingRepository): Response
    {
        $date = $request->request->get('date');
        $startTime = $request->request->get('startTime');
        $endTime = $request->request->get('endTime');
        $floor = $request->request->get('floor');
        $raum = $request->request->get('raum');
        $table = $request->request->get('table');

        if ($bookingRepository->findExistingBooking(
            new \DateTime($date),
            new \DateTime($startTime),
            new \DateTime($endTime),
            (int) $floor,
            (int) $raum,
            (int) $table
        )) {
            $this->addFlash('error', 'Booking already exists for this date, time, floor, room, and table!');
            return $this->redirectToRoute('bookings');
        }

        $booking = new Booking();
        $booking->setDate(new \DateTime($date));
        $booking->setStartTime(new \DateTime($startTime));
        $booking->setEndTime(new \DateTime($endTime));
        $booking->setFloor((int) $floor);
        $booking->setRoom((int) $raum);
        $booking->setTable((int) $table);
        $booking->setUser($this->getUser());

        $em->persist($booking);
        $em->flush();

        $this->addFlash('success', 'Booking successfully saved!');
        return $this->redirectToRoute('dashboard');
    }
}
