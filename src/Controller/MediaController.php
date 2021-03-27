<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Service\MediaHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("trick/{slug}/media/{id}/edit",
     *     options={"expose": true},
     *     name="media_edit",
     * )
     *
     * @param Request      $request
     * @param Media        $media
     * @param MediaHandler $mediaHandler
     * @param string       $slug
     *
     * @throws \JsonException
     *
     * @return Response
     */
    public function edit(Request $request, Media $media, MediaHandler $mediaHandler, string $slug): Response
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
                $this->addFlash('error', 'Erreur lors de la mise à jour du média');

                return $this->redirectToRoute('trick_edit', [
                    'slug' => $slug,
                ]);
            }

            $this->updateMedia($mediaHandler, $media, $form);

            return $this->redirectToRoute('trick_edit', [
                'slug' => $slug,
            ]);
        }

        return $this->render('tricks/_update_media_form.html.twig', [
            'mediaForm' => $form->createView(),
        ]);
    }

    /**
     * @param MediaHandler  $mediaHandler
     * @param Media         $media
     * @param FormInterface $form
     *
     * @throws \JsonException
     */
    private function updateMedia(MediaHandler $mediaHandler, Media $media, FormInterface $form): void
    {
        $result = $mediaHandler->updateMedia($media, $form);
        if (MediaHandler::MEDIA_UPDATED !== $result) {
            $this->addFlash('error', $result);
        } else {
            $this->addFlash('success', 'Média mis à jour');
        }
    }
}
