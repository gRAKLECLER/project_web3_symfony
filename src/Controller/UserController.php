<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    /**
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/user", name="app_user_index")
     */
    public function index(UserRepository $Repository): Response
    {
        $loggedUser = $this->getUser();
        $user = $Repository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $user,
            'loggedUser' => $loggedUser,
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/user/{id}", name="app_user_show")
     */
    public function showOneUser(User $user): Response
    {
        return $this->render('user/user_show.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/user/{id}/delete", name="app_user_delete")
     */
    public function deleteUser(User $user, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index');
    }

    /**
     * @return Response
     * @Route("/user_new", name="app_user_new")
     */
    public function createNewUser():Response
    {
        return $this->render('user/user_new.html.twig');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route ("/register", name="app_user_register", methods={"POST"})
     */
    public function submitUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = new User();
        $user->setFirstname($request->request->get('fistName'))
             ->setLastname($request->request->get('lastName'))
             ->setEmail($request->request->get('email'))
            ->setRoles((array)$request->request->get('role'))
            ->setPassword($this->hasher->hashPassword($user, $request->request->get('password')));


        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    /**
     * @param User $user
     * @return Response
     * @Route("/user/{id}/modify", name="app_user_modify")
     */
    public function modifyUser(User $user): Response
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
    public function updateUser(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user->setFirstname($request->request->get('fistName'))
            ->setLastname($request->request->get('lastName'))
            ->setEmail($request->request->get('email'))
            ->setRoles((array)$request->request->get('role'));


        $entityManager->flush();

        return $this->redirectToRoute('app_user_show', [
            'id' => $user->getId()
        ]);
    }


    /**
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/user/{id}/vote", name="app_user_vote", methods="POST")
     */
    public function userVote(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $vote = $request->request->get('vote');
        if ($vote === 'up'){
            $user->upVote();
        }
        if ($vote === 'down'){
            $user->downVote();
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_user_show', [
            'id' => $user->getId()
        ]);
    }
}
