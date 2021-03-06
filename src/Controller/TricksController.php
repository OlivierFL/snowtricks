<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Trick;
use App\Form\CommentFormType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Security\TrickVoter;
use App\Service\FileUploader;
use App\Service\TrickHandler;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
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

            $this->addFlash('success', Comment::COMMENT_SUBMITTED);
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
     * @param Request      $request
     * @param TrickHandler $trickHandler
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function create(Request $request, TrickHandler $trickHandler): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickHandler->handleTrick($trick, $form);

            $this->addFlash('success', Trick::NEW_TRICK_CREATED);

            return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tricks/{slug}/edit",
     *     name="trick_edit",
     *     options={"expose": true},
     *     priority=1
     * )
     *
     * @param Request      $request
     * @param Trick        $trick
     * @param TrickHandler $trickHandler
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function edit(Request $request, Trick $trick, TrickHandler $trickHandler): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $oldTrick = clone $trick;

        $medias = $trick->getTricksMedia()->getValues();

        foreach ($trick->getTricksMedia() as $trickMedia) {
            $trick->removeTricksMedium($trickMedia);
        }

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        $trick = $this->addTricksMedia($trick, $medias);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickHandler->handleTrick($trick, $form);

            $this->addFlash('success', Trick::TRICK_UPDATED);

            return $this->redirectToRoute('trick_detail', ['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/edit.html.twig', [
            'trick' => $trick,
            'oldTrick' => $oldTrick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tricks/{id}/delete",
     *     name="trick_delete",
     *     options={"expose": true},
     *     priority=1
     * )
     *
     * @param Trick                  $trick
     * @param EntityManagerInterface $em
     * @param FileUploader           $fileUploader
     *
     * @return Response
     */
    public function delete(Trick $trick, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted(TrickVoter::TRICK_DELETE, $trick);

        foreach ($trick->getTricksMedia() as $trickMedia) {
            if (Media::IMAGE === $trickMedia->getMedia()->getType()) {
                $fileUploader->remove($trickMedia->getMedia()->getUrl());
            }
        }

        $em->remove($trick);
        $em->flush();

        $this->addFlash('success', Trick::TRICK_DELETED);

        return $this->redirectToRoute('home');
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
            $this->getParameter('comments_list_limit')
        );
    }

    /**
     * @param array $medias
     * @param Trick $trick
     *
     * @return Trick
     */
    private function addTricksMedia(Trick $trick, array $medias): Trick
    {
        foreach ($medias as $medium) {
            $trick->addTricksMedium($medium);
        }

        return $trick;
    }
}
