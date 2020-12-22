<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository $repository
     * @param Request         $request
     *
     * @return Response
     */
    public function index(TrickRepository $repository, Request $request): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $paginator = $repository->findLastTricksPaginated($page);

        return $this->render('layout/index.html.twig', [
            'tricks' => $paginator,
        ]);
    }
}
