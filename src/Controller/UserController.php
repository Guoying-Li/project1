<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Form\UserCreateType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user')]
    public function index(UserRepository $userRepository, Request $request): Response
    {
        $sortBy = $request->query->get('sort');
        $sortDir = $request->query->get('direction');

        $queryBuilder = $userRepository->createQueryBuilder('u');

        if ($sortBy && $sortDir) {
            $queryBuilder->orderBy('u.' . $sortBy, $sortDir);
        }

        $users = $queryBuilder->getQuery()->getResult();
        $user = new User();
        $form = $this->createForm(UserCreateType::class, $user, ['action' => $this -> generateUrl('app_user_create')]);
        $form->handleRequest($request);

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: 'app_user_delete')]
    public function delete(EntityManagerInterface $entityManager,UserRepository $userRepository, User $user, int $id): Response
    {
        $user= $userRepository->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }
    #[Route('/create', name: 'app_user_create')]
public function CreateUser(MailerInterface $mailer, UserPasswordHasherInterface $hasher, EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request): Response
{
    $users = $userRepository->findAll();
    $user = new User();
    $form = $this->createForm(UserCreateType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();

        $user->setPassword($hasher->hashPassword($user, $user->getPassword()));
        $entityManager->persist($user);
        $entityManager->flush();

         $this->addFlash('success', 'Le compte a été créé');

        return $this->redirectToRoute('app_user');
    }

    return $this->render('user/index.html.twig', [
        'form' => $form,
        'users' => $users,
    ]);
}
#[Route('/setRoles/{id}', name: 'app_user_set_roles')]
    public function setRoles(EntityManagerInterface $entityManager, Request $request, User $user, int $id): Response
    {
        // $tokenTest = $this->isCsrfTokenValid('role-' . $id, $request->request->get('_token'));
        // if(!$tokenTest) {
        //     throw new BadRequestHttpException();
        // }

        $roles = [];
        if ($request->request->get($user->getId() . '-roles-user')) {
            $roles[] = "ROLE_USER";
        }

        if ($request->request->get($user->getId() . '-roles-admin')) {
            $roles[] = "ROLE_ADMIN";
        }

        $user->setRoles($roles);

        $entityManager->flush();
        // $this->addFlash('success', 'roles maj');
        return $this->redirectToRoute('app_user');
    }





}
