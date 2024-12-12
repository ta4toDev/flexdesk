<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LocaleController extends AbstractController
{
    #[Route('/switch-locale/{locale}', name: 'switch_locale', requirements: ['locale' => 'en|de'])]
    public function switchLocale(string $locale, Request $request): RedirectResponse
    {
        // Sprache in der Session speichern
        $request->getSession()->set('_locale', $locale);

        // Zur vorherigen Seite umleiten oder zur Startseite
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('home'));
    }
}