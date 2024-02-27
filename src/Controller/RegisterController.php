<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(MailerInterface $mailer, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request): Response
    {
        $user = new User();
        $form = $this-> createForm(RegisterType::class, $user);
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form->isValid()) {
            $user= $form -> getData();
            $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
            $entityManager ->persist($user);
            $entityManager -> flush();

            $email = new TemplatedEmail();
            $email -> from('contact@gg.net')
            ->to($user -> getEmail())
            ->subject('bienvenue')
            ->text('votre compte a bien été créé ')
            ->html('<h1>Salut</h1>'. $user->getLastname());
            $mailer ->send($email);

            return $this-> redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'register' => $form,
        ]);
    }
}
