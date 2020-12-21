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
        $offset = max(0, $request->query->getInt('offset', 0));
        $paginator = $repository->findLastTricksPaginated($offset);

        return $this->render('layout/index.html.twig', [
            'tricks' => $paginator,
        ]);
    }
}
