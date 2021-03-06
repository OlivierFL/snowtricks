<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     *
     * @param TrickRepository   $trickRepository
     * @param CommentRepository $commentRepository
     *
     * @return Response
     */
    public function index(TrickRepository $trickRepository, CommentRepository $commentRepository): Response
    {
        $tricks = $trickRepository->findBy([], ['createdAt' => 'DESC'], 3);
        $comments = $commentRepository->findBy([], ['createdAt' => 'DESC'], 3);

        return $this->render('admin/dashboard.html.twig', [
            'tricks' => $tricks,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/tricks", name="tricks")
     *
     * @param TrickRepository $trickRepository
     *
     * @return Response
     */
    public function showTricks(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/tricks.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/comments", name="comments")
     *
     * @param CommentRepository $commentRepository
     *
     * @return Response
     */
    public function showComments(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/comments.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/moderate-comment/{isValid}",
     *     name="moderate_comment",
     *     options={"expose": true},
     * )
     *
     * @param CommentRepository $commentRepository
     * @param Request           $request
     *
     * @return Response
     */
    public function moderateComment(CommentRepository $commentRepository, Request $request): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $request->request->get('id')]);
        if (!$comment) {
            throw new NotFoundHttpException();
        }
        $isValid = (bool) $request->attributes->get('isValid');
        $comment->setIsValid($isValid);

        if ($isValid) {
            $this->addFlash('success', Comment::COMMENT_PUBLISHED);
        } else {
            $this->addFlash('success', Comment::COMMENT_MODERATED);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        return $this->redirectToRoute('admin_comments');
    }
}
