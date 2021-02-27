<?php

namespace App\Form;

use App\Entity\Media;
use App\Service\VideoHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MediaType extends AbstractType
{
    /**
     * @var VideoHelper
     */
    public VideoHelper $videoHelper;

    /**
     * MediaType constructor.
     *
     * @param VideoHelper $videoHelper
     */
    public function __construct(VideoHelper $videoHelper)
    {
        $this->videoHelper = $videoHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $media = $event->getData();
                if ($form->getConfig()->getOption('new')) {
                    $form->add('type', ChoiceType::class, [
                        'label' => 'Media type',
                        'choices' => [
                            'Picture' => Media::IMAGE,
                            'Video' => Media::VIDEO,
                        ],
                        'expanded' => true,
                        'multiple' => false,
                        'required' => true,
                        'constraints' => [
                            new NotBlank(['message' => 'Choose a media type']),
                        ],
                    ]);
                }
                if (
                    (null !== $media && Media::IMAGE === $media->getType())
                    || $form->getConfig()->getOption('new')
                ) {
                    $form->add('image', FileType::class, [
                        'mapped' => false,
                        'required' => false,
                        'label' => 'Add an image',
                        'help' => 'Please select an image file',
                        'constraints' => [
                            new NotBlank([
                                'message' => 'An empty file is not allowed',
                                'groups' => ['image'],
                            ]),
                            new File([
                                'maxSize' => '2M',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/webp',
                                    'image/png',
                                    'image/gif',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid image file',
                                'groups' => ['image'],
                            ]),
                        ],
                    ]);
                }
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
                }

                $form->add('altText', TextType::class, [
                    'label' => 'Alternative text',
                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'This field can not be blank',
                            'groups' => ['image'],
                        ]),
                    ],
                ]);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $media = $event->getData();
                if (Media::VIDEO === $media['type']) {
                    $videoData = $this->videoHelper->getVideoData($media['video_url']);
                    $media['altText'] = $videoData['title'] ?? 'Video';
                    $event->setData($media);
                }
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'new' => false,
            'validation_groups' => function (FormInterface $form) {
                if ($form->getConfig()->getOption('new')) {
                    $this->addConstraints($form);
                }

                return ['Default'];
            },
        ]);
    }

    /**
     * @param FormInterface $form
     *
     * @return null|string[]
     */
    private function addConstraints(FormInterface $form): ?array
    {
        if (
            Media::IMAGE === $form->get('type')->getData()
            && (null === $form->get('image')->getData() || null === $form->get('altText')->getData())
        ) {
            return ['Default', 'image'];
        }

        if (
            Media::VIDEO === $form->get('type')->getData()
            && (null === $form->get('video_url')->getData()
                || !$this->videoHelper->checkUrl($form->get('video_url')->getData()))
        ) {
            return ['Default', 'video'];
        }

        return ['Default'];
    }
}
