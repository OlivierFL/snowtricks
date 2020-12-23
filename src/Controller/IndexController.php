<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository    $repository
     * @param Request            $request
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(TrickRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $page = max(1, $request->query->getInt('page', 1));

        $query = $repository->findBy([], ['createdAt' => 'DESC']);

        $tricksPaginated = $paginator->paginate(
            $query,
            $page,
            PaginatorInterface::DEFAULT_LIMIT_VALUE
        );

        return $this->render('layout/index.html.twig', [
            'tricks' => $tricksPaginated,
        ]);
    }
}
