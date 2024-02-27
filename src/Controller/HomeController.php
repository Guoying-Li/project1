<?php

namespace App\Controller;


use App\Security\EmailVerifier;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/test_mail', name: 'app_test_mail')]
    public function sendMailer(MailerInterface $mailer, EmailVerifier $eV): Response
    {
        $email = new Email();
        $email -> from('contact@gg.net')
               ->to('jdoe@gmail.com')
               ->subject('reset pass')
               ->text('salut debil, password oublie? ')
               ->html('<h1>Cou cou</h1>');
        $mailer ->send($email);

         return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
