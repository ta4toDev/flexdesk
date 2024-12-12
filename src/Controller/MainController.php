<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig',[
            'controller_name' => 'MainController',
        ]);
    }

}