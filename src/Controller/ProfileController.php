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
            ->add('firstName', TextType::class, [
                'label' => 'firstName',
                'disabled' => true
            ])
            ->add('lastName', TextType::class , [
                'label' => 'lastName',
                'disabled' => true
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail-Adresse',
                'disabled' => true
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'phone'
            ])
            ->add('position', TextType::class , [
                'label' => 'Position',
                'disabled' => false,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Upload photo',
                'mapped' => false,
                'required' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
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
            $file = $form->get('photo')->getData();
            if ($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Verschieben der hochgeladenen Datei in das Zielverzeichnis
                $file->move(
                    $this->getParameter('photos_directory'),
                    $fileName
                );

                // Speichere den Dateinamen in der Datenbank
                $user->setPhoto($fileName);
            }
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Profile successfully updated!');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
