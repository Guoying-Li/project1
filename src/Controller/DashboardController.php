<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(Request $request): Response
    {
        $lastPictures = $request->query->get('last_pictures', 4);
        $lastEvents = $request->query->get('last_events', 3);

        return $this->render('dashboard/index.html.twig', [
            'last_pictures' => $lastPictures,
            'last_events'   => $lastEvents
        ]);
    }
}
