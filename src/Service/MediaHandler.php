<?php

namespace App\Service;

use App\Entity\Media;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $em, VideoHelper $videoHelper)
    {
        $this->em = $em;
        $this->videoHelper = $videoHelper;
        $this->validator = Validation::createValidator();
    }

    /**
     * @param Request $request
     * @param string  $key
     *
     * @return bool
     */
    public function validateAltText(Request $request, string $key): bool
    {
        if (isset($request->request->get($key)['altText'])) {
            $violations = $this->validator->validate(
                $request->request->get($key)['altText'],
                [
                    new NotBlank(),
                ]
            );

            return empty($violations[0]);
        }

        return true;
    }

    /**
     * @param FormInterface $form
     * @param Trick         $trick
     *
     * @throws JsonException
     *
     * @return string
     */
    public function handleMediaUpdate(FormInterface $form, Trick $trick): string
    {
        /** @var Media $media */
        $media = $form->getData();
        $type = $media->getType();

        if (Media::YOUTUBE_VIDEO === $type || Media::VIMEO_VIDEO === $type) {
            return $this->updateVideoMedia($form, $media, $trick);
        }

        $this->em->persist($trick);
        $this->em->flush();

        return self::MEDIA_UPDATED;
    }

    /**
     * @param FormInterface $form
     * @param Media         $media
     * @param Trick         $trick
     *
     * @throws JsonException
     *
     * @return string
     */
    private function updateVideoMedia(FormInterface $form, Media $media, Trick $trick): string
    {
        $url = $form->get('video_url')->getData();
        $violations = $this->validator->validate(
            $url,
            [
                new Regex(
                    [
                        'pattern' => VideoHelper::PATTERN,
                        'message' => 'URL invalide !',
                    ],
                ),
                new NotBlank([
                    'message' => 'Le champ URL ne doit pas être vide !',
                ]),
            ]
        );

        if (!empty($violations[0])) {
            return $violations[0]->getMessage();
        }

        $videoData = $this->videoHelper->getVideoData($url);
        $media->setUrl($videoData['id']);
        $media->setAltText($videoData['title']);
        $media->setType($videoData['type']);

        $this->em->persist($trick);
        $this->em->flush();

        return self::MEDIA_UPDATED;
    }
}
