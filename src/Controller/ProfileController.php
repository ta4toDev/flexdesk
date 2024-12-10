<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;



class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function profile(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();  // Hole den aktuell eingeloggten Benutzer

        // Formular
        $form = $this->createFormBuilder($user)
            ->add('vorname', TextType::class, [
                'label' => 'Vorname',
                'disabled' => true
            ])
            ->add('name', TextType::class , [
                'label' => 'Nachname',
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail-Adresse',
                'disabled' => true
            ])
            ->add('telefonnummer', TextType::class, [
                'label' => 'Telefonnummer'
            ])
            ->add('position', TextType::class , [
                'label' => 'Position',
                'disabled' => false,
            ])
            ->add('foto', FileType::class, [
                'label' => 'Profilbild hochladen',
                'mapped' => false,
                'required' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'first_options' => ['label' => 'Passwort'],
                'second_options' => ['label' => 'Passwort wiederholen'],
            ])
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Passwort nur ändern, wenn es im Formular ausgefüllt wurde
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword)
                );
            }
            // Foto-Upload verarbeiten
            $file = $form->get('foto')->getData();
            if ($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Verschieben der hochgeladenen Datei in das Zielverzeichnis
                $file->move(
                    $this->getParameter('photos_directory'),
                    $fileName
                );

                // Speichere den Dateinamen in der Datenbank
                $user->setFoto($fileName);
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profil erfolgreich aktualisiert!');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
