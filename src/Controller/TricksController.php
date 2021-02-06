<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentFormType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks/{slug}",
     *     options={"expose": true},
     * name="trick_detail")
     *
     * @param Request            $request
     * @param Trick              $trick
     * @param CommentRepository  $commentRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function show(
        Request $request,
        Trick $trick,
        CommentRepository $commentRepository,
        PaginatorInterface $paginator
    ): Response {
        $page = max(1, $request->query->getInt('page', 1));
        $comments = $this->getCommentsPaginated($commentRepository, $trick, $paginator, $page);

        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $comment->setIsValid(false);
            $comment->setTrick($trick);
            $comment->setAuthor($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été soumis pour validation');
        }

        return $this->render('tricks/trick.html.twig', [
            'trick' => $trick,
            'comments' => $comments,
            'comment_form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tricks/new",
     *     name="trick_new",
     *     priority=1
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setAuthor($this->getUser());
            dd($trick);
        }

        return $this->render('tricks/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param CommentRepository  $commentRepository
     * @param Trick              $trick
     * @param PaginatorInterface $paginator
     * @param                    $page
     *
     * @return PaginationInterface
     */
    private function getCommentsPaginated(
        CommentRepository $commentRepository,
        Trick $trick,
        PaginatorInterface $paginator,
        $page
    ): PaginationInterface {
        $commentsQuery = $commentRepository->findBy([
            'trick' => $trick,
            'isValid' => true,
        ], [
            'updatedAt' => 'DESC',
        ]);

        return $paginator->paginate(
            $commentsQuery,
            $page,
            2
        );
    }
}
