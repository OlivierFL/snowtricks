<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/{slug}",
     *     options={"expose"=true},
     *     name="trick_detail")
     * @param Request            $request
     * @param Trick              $trick
     * @param CommentRepository  $commentRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function show(Request $request, Trick $trick, CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $page = max(1, $request->query->getInt('page', 1));

        $commentsQuery = $commentRepository->findBy(['trick' => $trick], ['updatedAt' => 'DESC']);

        $comments = $paginator->paginate($commentsQuery,
            $page,
            2);

        return $this->render('tricks/trick.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
        ]);
    }
}
