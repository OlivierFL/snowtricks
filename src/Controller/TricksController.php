<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks", name="tricks")
     */
    public function tricks(): Response
    {
        return $this->render('layout/tricks.html.twig');
    }

    /**
     * @Route("/trick", name="trick_detail")
     */
    public function trick(): Response
    {
        return $this->render('layout/trick_detail.html.twig');
    }
}
