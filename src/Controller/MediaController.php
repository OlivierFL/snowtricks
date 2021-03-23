<?php

namespace App\Controller;

use App\Entity\Media;
use App\Service\MediaHandler;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    /**
     * @Route("/media/{id}/edit",
     *     name="media_edit",
     * )
     *
     * @param Request      $request
     * @param Media        $media
     * @param MediaHandler $mediaHandler
     *
     * @throws JsonException
     *
     * @return Response
     */
    public function edit(Request $request, Media $media, MediaHandler $mediaHandler): Response
    {
        $data = $request->request->get('media_'.$media->getId());

        $result = $mediaHandler->updateMedia($data, $media);
        if (MediaHandler::MEDIA_UPDATED !== $result) {
            $this->addFlash('error', $result);
        } else {
            $this->addFlash('success', 'Média mis à jour');
        }

        return $this->redirectToRoute('trick_edit', [
            'slug' => $request->query->get('slug'),
        ]);
    }
}
