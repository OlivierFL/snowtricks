<?php

namespace App\Service;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;

class MediaHandler
{
    public const MEDIA_UPDATED = 'media updated';
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var VideoHelper
     */
    private VideoHelper $videoHelper;

    public function __construct(EntityManagerInterface $em, VideoHelper $videoHelper)
    {
        $this->em = $em;
        $this->videoHelper = $videoHelper;
    }

    /**
     * @param array $data
     * @param Media $media
     *
     * @throws JsonException
     *
     * @return string
     */
    public function updateMedia(array $data, Media $media): string
    {
        $type = $media->getType();

        if (Media::IMAGE === $type) {
            $media->setAltText($data['altText']);
            $this->em->persist($media);
        }

        if (Media::YOUTUBE_VIDEO === $type || Media::VIMEO_VIDEO === $type) {
            $videoData = $this->videoHelper->getVideoData($data['video_url']);
            $media->setUrl($videoData['id']);
            $media->setAltText($videoData['title']);
            $media->setType($videoData['type']);
            $this->em->persist($media);
        }

        $this->em->flush();

        return self::MEDIA_UPDATED;
    }
}
