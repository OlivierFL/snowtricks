<?php

namespace App\Service;

use App\Entity\Media;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

class TrickHandler
{
    /**
     * @var FileUploader
     */
    private FileUploader $fileUploader;
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var VideoHelper
     */
    private VideoHelper $videoHelper;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(
        FileUploader $fileUploader,
        Security $security,
        VideoHelper $videoHelper,
        EntityManagerInterface $entityManager
    ) {
        $this->fileUploader = $fileUploader;
        $this->videoHelper = $videoHelper;
        $this->security = $security;
        $this->em = $entityManager;
    }

    /**
     * @param Trick         $trick
     * @param FormInterface $form
     *
     * @throws JsonException
     */
    public function handleNewTrick(Trick $trick, FormInterface $form): void
    {
        $trick->setAuthor($this->security->getUser());

        if ($form->get('tricksMedia')) {
            $trick = $this->handleMediaCollection($trick, $form);
        }

        $this->em->persist($trick);
        $this->em->flush();
    }

    /**
     * @param Trick         $trick
     * @param FormInterface $form
     *
     * @throws JsonException
     *
     * @return Trick
     */
    private function handleMediaCollection(Trick $trick, FormInterface $form): Trick
    {
        foreach ($form->get('tricksMedia') as $key => $media) {
            // The first field of the form is an image,
            // and is used to set the cover image of the Trick.
            // TODO rework setting cover image, current method is not valid if the first media is a video
            if ('0' === $media->getName()) {
                $trick->getTricksMedia()[0]->setIsCoverImage(true);
            }

            $type = $media->get('media')->getData()->getType();

            if (Media::IMAGE === $type) {
                $fileName = $this->handleImageUpload($media);
                $trick->getTricksMedia()[$key]->getMedia()->setUrl($fileName);
            } else {
                $data = $this->videoHelper->getVideoData($media->get('media')->get('video_url')->getData());
                $trick->getTricksMedia()[$key]->getMedia()->setUrl($data['id']);
                $trick->getTricksMedia()[$key]->getMedia()->setType($data['type']);
            }
        }

        return $trick;
    }

    /**
     * @param FormInterface $form
     *
     * @return string
     */
    private function handleImageUpload(FormInterface $form): string
    {
        return $this->fileUploader->upload($form->get('media')->get('image')->getData());
    }
}
