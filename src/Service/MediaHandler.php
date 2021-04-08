<?php

namespace App\Service;

use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\TricksMedia;
use App\Repository\TricksMediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaHandler
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var VideoHelper
     */
    private VideoHelper $videoHelper;
    /**
     * @var TricksMediaRepository
     */
    private TricksMediaRepository $tricksMediaRepository;
    /**
     * @var FileUploader
     */
    private FileUploader $fileUploader;

    public function __construct(EntityManagerInterface $em, VideoHelper $videoHelper, TricksMediaRepository $repository, FileUploader $fileUploader)
    {
        $this->em = $em;
        $this->videoHelper = $videoHelper;
        $this->tricksMediaRepository = $repository;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param Media         $media
     * @param FormInterface $form
     *
     * @throws JsonException
     *
     * @return string
     */
    public function updateMedia(Media $media, FormInterface $form): string
    {
        $type = $media->getType();

        if (Media::IMAGE === $type) {
            $media->setAltText($form->get('altText')->getData());
            $this->em->persist($media);
        }

        if (Media::YOUTUBE_VIDEO === $type || Media::VIMEO_VIDEO === $type) {
            $videoData = $this->videoHelper->getVideoData($form->get('video_url')->getData());
            $media->setUrl($videoData['id']);
            $media->setAltText($videoData['title']);
            $media->setType($videoData['type']);
            $this->em->persist($media);
        }

        $this->em->flush();

        return Media::MEDIA_UPDATED;
    }

    /**
     * @param FormInterface $form
     * @param Trick         $trick
     *
     * @return string
     */
    public function updateCoverImage(FormInterface $form, Trick $trick): string
    {
        $oldCoverImage = $this->tricksMediaRepository->findOneBy([
            'isCoverImage' => true,
            'trick' => $trick->getId(),
        ]);
        $oldCoverImage->setIsCoverImage(false);
        $this->em->persist($oldCoverImage);

        /** @var TricksMedia $tricksMedia */
        $tricksMedia = $form->get('cover_image')->getData();
        $newUploadedFile = $form->get('new_cover_image')->get('image')->getData();
        $newUploadedAltText = $form->get('altText')->getData();
        if ($newUploadedFile && $newUploadedAltText) {
            $this->handleNewCoverImage($newUploadedFile, $newUploadedAltText, $trick);
        } else {
            $tricksMedia->setIsCoverImage(true);
            $this->em->persist($tricksMedia);
        }
        $this->em->flush();

        return TricksMedia::COVER_IMAGE_UPDATED;
    }

    /**
     * @param UploadedFile $newUploadedCoverImage
     * @param string       $newUploadedAltText
     * @param Trick        $trick
     */
    private function handleNewCoverImage(UploadedFile $newUploadedCoverImage, string $newUploadedAltText, Trick $trick): void
    {
        $fileName = $this->fileUploader->upload($newUploadedCoverImage);

        $media = new Media();
        $media->setUrl($fileName);
        $media->setAltText($newUploadedAltText);
        $media->setType(Media::IMAGE);
        $this->em->persist($media);

        $trickMedia = new TricksMedia();
        $trickMedia->setMedia($media);
        $trickMedia->setIsCoverImage(true);

        $this->em->persist($trickMedia);

        $trick->addTricksMedium($trickMedia);

        $this->em->persist($trick);
    }
}
