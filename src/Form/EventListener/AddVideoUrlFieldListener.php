<?php

namespace App\Form\EventListener;

use App\Entity\Media;
use App\Service\VideoHelper;
use JsonException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AddVideoUrlFieldListener implements EventSubscriberInterface
{
    /**
     * @var VideoHelper
     */
    private VideoHelper $videoHelper;

    public function __construct()
    {
        $this->videoHelper = new VideoHelper();
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'onPostSetData',
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $media = $event->getData();
        if (
            $form->getConfig()->getOption('new')
            || (null !== $media && (Media::VIMEO_VIDEO === $media->getType() || Media::YOUTUBE_VIDEO === $media->getType()))
        ) {
            $form->add('video_url', TextType::class, [
                'label' => 'Video URL',
                'help' => 'Paste Youtube or Vimeo video URL, or embed tag',
                'trim' => true,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Regex(
                        [
                            'pattern' => VideoHelper::PATTERN,
                            'message' => 'Invalid URL',
                            'groups' => ['video'],
                        ],
                    ),
                    new NotBlank([
                        'message' => 'This field can not be blank',
                        'groups' => ['video'],
                    ]),
                ],
            ]);

            if (null !== $media && Media::YOUTUBE_VIDEO === $media->getType()) {
                $form->get('video_url')->setData('https://www.youtube.com/embed/'.$media->getUrl());
            }

            if (null !== $media && Media::VIMEO_VIDEO === $media->getType()) {
                $form->get('video_url')->setData('https://player.vimeo.com/video/'.$media->getUrl());
            }
        }
    }

    /**
     * @param FormEvent $event
     *
     * @throws JsonException
     */
    public function onPreSubmit(FormEvent $event): void
    {
        $media = $event->getData();
        if (isset($media['type']) && Media::VIDEO === $media['type']) {
            $videoData = $this->videoHelper->getVideoData($media['video_url']);
            $media['altText'] = $videoData['title'] ?? 'Video';
            $event->setData($media);
        }
    }
}
