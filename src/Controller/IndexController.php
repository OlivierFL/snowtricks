<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository $repository
     *
     * @return Response
     */
    public function index(TrickRepository $repository): Response
    {
        return $this->render('layout/index.html.twig', [
            'tricks' => $repository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }
}
