<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Utils\UploadUtils;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @param AdRepository $AdRepository
     * @return Response
     * @Route("/ads", name="app_ads_index")
     */
    public function index(AdRepository $Repository): Response
    {

        $ads = $Repository->findAll();

        return $this->render('ads/list_all_ads.html.twig', [
            'ads' => $ads,
        ]);
    }

    /**
     * @param Ad $ad
     * @return Response
     * @Route("/ad/{id}", name="app_ad_show")
     */
    public function show(Ad $ad): Response
    {
        return $this->render('ads/ad.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/ad/{id}/delete", name="app_ad_delete")
     */
    public function delete(Ad $ad, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($ad);
        $entityManager->flush();

        return $this->redirectToRoute('app_ad_index');
    }

    /**
     * @return Response
     * @Route("/ad_new", name="app_ad_new")
     */
    public function new():Response
    {
        return $this->render('ads/ad_new.html.twig');
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route ("/ad_create", name="app_ad_create", methods={"POST"})
     */
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $ad = new Ad();

        $ad->setTitle($request->request->get('title'))
            ->setPhoto($request->request->get('photo'))
            ->setDescription($request->request->get('description'))
            ->setPrice($request->request->get('price'))
            ->setPublishedDate($request->request->get('published_date'))
            ->setTags($request->request->get('description'));

        $entityManager->persist($ad);
        $entityManager->flush();

        return $this->redirectToRoute('ads', [
            'id' => $ad->getId()
        ]);

    }

    /**
     * @param Ad $Ad
     * @return Response
     * @Route("/ad/{id}/modify", name="app_ad_modify")
     */
    public function modify(Ad $ad): Response
    {
        return $this->render('ads/ads_modify.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @param Ad $ad
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route ("/ad/{id}/update", name="app_ad_update", methods={"POST"})
     */
    public function update(Ad $ad, EntityManagerInterface $entityManager, Request $request): Response
    {
        $ad->setTitle($request->request->get('title'))
            ->setPhoto($request->request->get('photo'))
            ->setDescription($request->request->get('description'))
            ->setPrice($request->request->get('price'))
            ->setPublishedDate(setDate(new \DateTime('now')))
            ->setTags($request->request->get('description'));

        $entityManager->flush();

        return $this->redirectToRoute('app_ad_show', [
            'id' => $ad->getId()
        ]);
    }

    /**
     * @param Ad $ad
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @Route ("/ad/upload", name="app_ad_update", methods={"POST"})
     */
    public function uploadImages(Ad $ad, EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @varb UploadFile $newFile */

        $newFile = $request->files->get('image');
        $destination = $this->getParameter('kernel.project_dir').'public/uploads';
        $originalName = $newFile->getClientOriginalName();

        $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);

        $fileName = $baseFileName . '-' . uniqid() . '.' . $newFile->guessExtension();

        $newfile->move($destination, $fileName);

        return new Response('file moved');
    }

    public function findAdByName(AdRepository $Repository, Request $request): Response
    {

        $ads = $request->request->get('search');
        $articlesFound = $Repository->searchArticles($ads);

        return $this->render('ads/search.html.twig', [
            'articlesFound' => $articlesFound,
        ]);
    }

     /**
     * @param Ad $ad
     * @param App\Entity\User
     * @return Response
     * @Route("/ad/{id}", name="app_ad")
     */
    public function showOneAd(Ad $ad): Response
    {

        return $this->render('ads/ad.html.twig', [
            'ad' => $ad,
            'user' => $this->user->getUser()
        ]);
    }
}
