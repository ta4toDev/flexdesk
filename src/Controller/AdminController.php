<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Buchung;
use App\Repository\BuchungRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AdminController extends AbstractController
{
    #[Route('/admin/benutzer_erstellen', name: 'benutzer_erstellen')]
    public function createUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('vorname', TextType::class)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Neuer Benutzer erfolgreich erstellt!');
            return $this->redirectToRoute('benutzer_liste');
        }

        return $this->render('admin/create_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/benutzer-liste', name: 'benutzer_liste')]
    public function listUsers(EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $benutzer = $em->getRepository(User::class)->createQueryBuilder('u')
            ->where('u.roles NOT LIKE :role')
            ->setParameter('role', '%"ROLE_ADMIN"%')
            ->getQuery()
            ->getResult();

        return $this->render('admin/user_liste.html.twig', [
            'benutzer' => $benutzer,
        ]);
    }

    #[Route('/admin/benutzer-bearbeiten/{id}', name: 'benutzer_bearbeiten')]
    public function editUser(User $user, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createFormBuilder($user)
            ->add('vorname', TextType::class)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $em->flush();

            $this->addFlash('success', 'Benutzer erfolgreich bearbeitet!');
            return $this->redirectToRoute('benutzer_liste');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/benutzer-loeschen/{id}', name: 'benutzer_loeschen')]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Benutzer erfolgreich gelöscht!');
        return $this->redirectToRoute('benutzer_liste');
    }
    #[Route('/admin/buchungsuebersicht', name: 'buchungsuebersicht')]
    public function bookingsOverview(EntityManagerInterface $em): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        // Alle Buchungen abrufen
        $buchungen = $em->getRepository(Buchung::class)->findAll();

        return $this->render('admin/bookings_overview.html.twig', [
            'buchungen' => $buchungen,
        ]);
    }

}
