<?php
namespace App\Controller;

use DateTime;
use App\Entity\Picture;
use App\Form\PictureType;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController
{

    #[Route('/picture', name: 'app_picture')]
    public function index(EntityManagerInterface $entityManager, PictureRepository $pictureRepository, Request $request): Response

    {
        
        $picture = new Picture();
        $form = $this -> createForm(PictureType::class, $picture);
        $form -> handleRequest($request);

        if($form-> isSubmitted() && $form -> isValid()) {
            $picture = $form -> getData();
            $entityManager -> persist($picture);
            $entityManager -> flush();

             return $this -> redirectToRoute('picture_list');
         
        }
     
        return $this->render('picture/index.html.twig', [
            'picForm' => $form,
            
            
        ]);
    }

    #[Route('/pictureList', name: 'picture_list')]

    public function list( PictureRepository $pictureRepository,  Request $request) :Response
    {
         $page = $request->query->getInt('page', 1); 
        $limit = 10;
    
         $pictures = $pictureRepository->findAll();
    
         $totalItems = count($pictures);
         $totalPages = ceil($totalItems / $limit);
    
         $offset = ($page -1) * $limit;
         $paginatedPictures = array_slice($pictures, $offset, $limit);
        //  $picture = $pictureRepository->paginations(10, 1);
    
        return $this->render('picture/list.html.twig', [
            'pictures' => $paginatedPictures,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ]);
        

    }
    #[Route('/pictureCard', name: 'picture_card')]
    public function card( PictureRepository $pictureRepository,  Request $request) :Response
    {

        $page = $request->query->getInt('page', 1); 
        $limit = 9;
    
        $pictures = $pictureRepository->findAll();
    
        $totalItems = count($pictures);
        $totalPages = ceil($totalItems / $limit);
    
        $offset = ($page -1) * $limit;
        $paginatedPictures = array_slice($pictures, $offset, $limit);


        return $this->render('picture/card.html.twig', [
            'pictures' => $paginatedPictures,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ]);
        

    }

    #[Route('/picture/{id}/delete', name: 'picture_delete')]
    public function delete(EntityManagerInterface $entityManager, PictureRepository $pictureRepository, int $id): Response
    {
        $picture = $pictureRepository->find($id);
        $entityManager->remove($picture);
        $entityManager->flush();
        return $this->redirectToRoute('picture_list');
    }
    

    #[Route('/picture/{id}edit', name: 'picture_edit')]
    public function edit(EntityManagerInterface $entityManager, PictureRepository $pictureRepository, Request $request, int $id) :Response
    {
        $picture = $pictureRepository ->find($id);
        $form = $this -> createForm(PictureType::class, $picture);
        $form -> handleRequest($request);
        if($form-> isSubmitted() && $form -> isValid()) {
            $entityManager -> persist($picture);
            $entityManager -> flush();

             return $this -> redirectToRoute('picture_list');
         
        }

             return $this->render('picture/index.html.twig', [
            'picForm' => $form,
            
            
        ]);


    }


    
}
