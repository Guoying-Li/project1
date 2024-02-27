<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Form\AddPictureType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(EntityManagerInterface $entityManager, EventRepository $eventRepository, Request $request): Response
    {
        // $user = $this->getUser();
        $event = new Event();
        $form = $this -> createForm(EventType::class, $event);
        $form -> handleRequest($request);

        if($form -> isSubmitted() && $form -> isValid()) {
            $event = $form -> getData();
            $entityManager -> persist($event);
            $entityManager -> flush();
            
            return $this -> redirectToRoute('event_list');
        }
        return $this->render('event/index.html.twig', [
            'picForm' => $form,
        ]);
    }

    #[Route('/eventList', name: 'event_list')]
    #[IsGranted('view', 'event')]
    public function list(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
    
        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }
    
    #[Route('/event/{id}/handleAddPicture', name: 'app_event_handleAddPicture')]
    
    public function handleAddPicture (EntityManagerInterface $entityManager, Event $event,  Request $request, int $id) :Response
    {
        $form = $this ->createForm(AddPictureType::class, $event);
        $form -> handleRequest($request);
        $picForm = $this ->createFrom(AddPictureType::class, $event,[
            'action' => $this ->generateUrl('app_event_handleAddPicture', ['id' => $event ->getId()])
        ]);
        if($form -> isSubmitted() && $form -> isValid())
        {
            $pictures = $form -> getData() -> getPictures();
            foreach ($pictures as $p) {
                $p->setEvent($event);
            }

        }
        return $this -> redirectToRoute('event_list', ['id' => $event ->getId()]);
    }
    #[Route('/event/{id}edit', name: 'event_edit')]

    public function edit (Event $event, EntityManagerInterface $entityManager, EventRepository $eventRepository,  Request $request, int $id) :Response
    {
        $event =$eventRepository ->find($id);
        $form = $this -> createForm(EventType::class, $event);
   
        $form -> handleRequest($request);
        
        if($form-> isSubmitted() && $form -> isValid()) {
            $entityManager -> persist($event);
            $entityManager -> flush();

             return $this -> redirectToRoute('event_list');
         
        }

             return $this->render('event/index.html.twig', [
            'picForm' => $form,
            
            
        ]);
    }

    #[Route('/event/{id}/delete', name: 'event_delete')]
    public function delete(EntityManagerInterface $entityManager, EventRepository $eventRepository, int $id): Response
    {
        $event = $eventRepository->find($id);
        $entityManager->remove($event);
        $entityManager->flush();
        return $this->redirectToRoute('event_list');
    }

}
