<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\TricksMedia;
use App\Form\CoverImageType;
use App\Form\MediaType;
use App\Repository\TrickRepository;
use App\Service\MediaHandler;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @IsGranted("IS_AUTHENTICATED_FULLY") */
class MediaController extends AbstractController
{
    /**
     * @var MediaHandler
     */
    private MediaHandler $mediaHandler;

    public function __construct(MediaHandler $mediaHandler)
    {
        $this->mediaHandler = $mediaHandler;
    }

    /**
     * @Route("trick/{slug}/media/{id}/edit",
     *     options={"expose": true},
     *     name="media_edit",
     * )
     *
     * @param Request $request
     * @param Media   $media
     * @param string  $slug
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function edit(Request $request, Media $media, string $slug): Response
    {
        $form = $this->createForm(MediaType::class, $media, [
            'new' => false,
            'action' => $this->generateUrl('media_edit', [
                'id' => $media->getId(),
                'slug' => $slug,
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->handleErrors($form);

                return $this->redirectToRoute('trick_edit', [
                    'slug' => $slug,
                ]);
            }

            $result = $this->mediaHandler->updateMedia($media, $form);

            $this->handleResult($result);

            return $this->redirectToRoute('trick_edit', [
                'slug' => $slug,
            ]);
        }

        return $this->render('tricks/_update_media_form.html.twig', [
            'mediaForm' => $form->createView(),
            'mediaType' => $media->getType(),
        ]);
    }

    /**
     * @Route("trick/{id}/cover-image/edit",
     *     options={"expose": true},
     *     name="cover_edit",
     * )
     *
     * @param Request $request
     * @param Trick   $trick
     *
     * @return Response
     */
    public function editCoverImage(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(CoverImageType::class, $trick, [
            'action' => $this->generateUrl('cover_edit', [
                'id' => $trick->getId(),
            ]),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $this->handleCoverImageErrors($form);

                return $this->redirectToRoute('trick_edit', [
                    'slug' => $trick->getSlug(),
                ]);
            }

            $result = $this->mediaHandler->updateCoverImage($form, $trick);

            $this->handleResult($result);

            return $this->redirectToRoute('trick_edit', [
                'slug' => $trick->getSlug(),
            ]);
        }

        return $this->render('tricks/_update_cover_form.html.twig', [
            'coverForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("trick/{slug}/media/{id}/delete",
     *     options={"expose": true},
     *     name="media_delete",
     * )
     *
     * @param string                 $slug
     * @param Media                  $media
     * @param TrickRepository        $trickRepository
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(string $slug, Media $media, TrickRepository $trickRepository, EntityManagerInterface $em): Response
    {
        $trick = $trickRepository->findOneBy(['slug' => $slug]);
        $em->remove($media);
        $em->flush();

        $this->addFlash('success', 'Media successfully deleted');

        return $this->redirectToRoute('trick_edit', [
            'slug' => $trick->getSlug(),
        ]);
    }

    /**
     * @param FormInterface $form
     */
    private function handleErrors(FormInterface $form): void
    {
        foreach ($form->getErrors() as $error) {
            $this->addFlash('error', $error->getMessage());
        }
    }

    /**
     * @param FormInterface $form
     */
    private function handleCoverImageErrors(FormInterface $form): void
    {
        foreach ($form->get('new_cover_image')->get('image')->getErrors() as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        foreach ($form->get('altText')->getErrors() as $error) {
            $this->addFlash('error', $error->getMessage());
        }
    }

    /**
     * @param string $result
     */
    private function handleResult(string $result): void
    {
        if (Media::MEDIA_UPDATED === $result) {
            $this->addFlash('success', $result);

            return;
        }

        if (TricksMedia::COVER_IMAGE_UPDATED === $result) {
            $this->addFlash('success', $result);

            return;
        }

        $this->addFlash('error', $result);
    }
}
