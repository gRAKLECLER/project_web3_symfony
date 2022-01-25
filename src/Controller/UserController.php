<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/user", name="app_user_index")
     */
    public function index(UserRepository $userRepository): Response
    {

        $user = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/user/{id}", name="app_user_show")
     */
    public function show(User $user): Response
    {
        return $this->render('user/user_show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/user/{id}/delete", name="app_user_delete")
     */
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
    }

    /**
     * @return Response
     * @Route("/user_new", name="app_user_new")
     */
    public function new():Response
    {
        return $this->render('user/user_new.html.twig');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route ("/user_create", name="app_user_create", methods={"POST"})
     */
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = new User();
        $user->setFirstname($request->request->get('fistName'))
             ->setLastname($request->request->get('lastName'))
             ->setEmail($request->request->get('email'));

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_show', [
           'id' => $user->getId()
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/user/{id}/modify", name="app_user_modify")
     */
    public function modify(User $user): Response
    {
        return $this->render('user/user_modify.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route ("/user/{id}/update", name="app_user_update", methods={"POST"})
     */
    public function update(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user->setFirstname($request->request->get('fistName'))
            ->setLastname($request->request->get('lastName'))
            ->setEmail($request->request->get('email'));

        $entityManager->flush();

        return $this->redirectToRoute('app_user_show', [
            'id' => $user->getId()
        ]);
    }
}
