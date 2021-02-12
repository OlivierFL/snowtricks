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

        if ($form->get('coverImage')) {
            $trick = $this->handleCoverImageUpload($trick, $form);
        }

        if ($form->get('medias')) {
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
        foreach ($form->get('medias') as $media) {
            if (Media::IMAGE === $media->get('media_type')->getData()) {
                $trick = $this->handleImageUpload($trick, $media);
            } else {
                $data = $this->videoHelper->getVideoData($media->get('video_url')->getData());
                $video = $media->getData();
                /* @var Media $video */
                $video->setUrl($data['id'] ?? '');
                $video->setType($data['type'] ?? 'unknown');
            }
        }

        return $trick;
    }

    /**
     * @param Trick         $trick
     * @param FormInterface $form
     *
     * @return Trick
     */
    private function handleCoverImageUpload(Trick $trick, FormInterface $form): Trick
    {
        dd($form->get('coverImage'));
        $filename = $this->fileUploader->upload($form->get('coverImage')->getData());
        $coverImage = new Media();
        $coverImage->setUrl($filename);
        $coverImage->setType(Media::IMAGE);
        $coverImage->setAltText('Image de couverture');
        $coverImage->setTrick($trick);
        $trick->setCoverImage($coverImage);

        return $trick;
    }

    /**
     * @param Trick         $trick
     * @param FormInterface $form
     *
     * @return Trick
     */
    private function handleImageUpload(Trick $trick, FormInterface $form): Trick
    {
        $filename = $this->fileUploader->upload($form->get('image')->getData());
        $media = $form->getData();
        /* @var Media $media */
        $media->setUrl($filename);
        $media->setType(Media::IMAGE);
        $trick->addMedia($media);

        return $trick;
    }
}
