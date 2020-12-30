<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/{slug}",
     *     name="trick_detail")
     * @param Trick $trick
     *
     * @return Response
     */
    public function show(Trick $trick): Response
    {
        return $this->render('layout/trick_detail.html.twig', [
            'trick' => $trick,
        ]);
    }
}
