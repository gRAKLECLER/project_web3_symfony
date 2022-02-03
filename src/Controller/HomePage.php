<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomePage extends AbstractController
{
    /**
     * @return Response
     * @Route("/")
     */

    public function homepage(): Response
    {

    }
}
