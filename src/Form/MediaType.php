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
    public const HIDDEN_LABEL_STYLES = 'font-jura font-light text-2xl mb-2';
    public const LABEL_STYLES = 'block font-jura font-light text-xl';
    public const HIDDEN_INPUT_STYLES = 'border-b border-gray-500 text-gray-500 bg-gray-100 pl-2 py-3 focus:border-yellow-500 focus:shadow-md focus:ring-2 focus:ring-yellow-500 w-full';
    public const RADIO_STYLES = 'flex items-center text-gray-500 py-3 w-full';

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
        $builder->add('altText', TextType::class, [
            'label' => 'Alternative text',
            'label_attr' => [
                'class' => self::HIDDEN_LABEL_STYLES,
            ],
            'attr' => [
                'class' => self::HIDDEN_INPUT_STYLES,
            ],
            'required' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'This field can not be blank',
                    'groups' => ['image'],
                ]),
            ],
        ])
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $media = $event->getData();
                if ($form->getConfig()->getOption('new')) {
                    $form->add('media_type', ChoiceType::class, [
                        'label' => 'Media type',
                        'label_attr' => [
                            'class' => self::LABEL_STYLES,
                        ],
                        'attr' => [
                            'class' => self::RADIO_STYLES,
                        ],
                        'choices' => [
                            'Picture' => Media::IMAGE,
                            'Video' => Media::VIDEO,
                        ],
                        'expanded' => true,
                        'multiple' => false,
                        'required' => true,
                        'mapped' => false,
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
                        'label_attr' => [
                            'class' => self::HIDDEN_LABEL_STYLES,
                        ],
                        'attr' => [
                            'class' => self::HIDDEN_INPUT_STYLES,
                        ],
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
                        'label_attr' => [
                            'class' => self::HIDDEN_LABEL_STYLES,
                        ],
                        'attr' => [
                            'class' => self::HIDDEN_INPUT_STYLES,
                        ],
                        'help' => 'Paste Youtube or Vimeo video URL, or embed tag',
                        'help_attr' => [
                            'class' => 'flex items-center text-gray-500 text-sm font-light text-left mt-2',
                        ],
                        'trim' => true,
                        'required' => false,
                        'mapped' => false,
                        'constraints' => [
                            new Regex(
                                [
                                    'pattern' => VideoHelper::BASE_PATTERN,
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
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $media = $event->getData();
                if (Media::VIDEO === $media['media_type']) {
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
            Media::IMAGE === $form->get('media_type')->getData()
            && (null === $form->get('image')->getData() || null === $form->get('altText')->getData())
        ) {
            return ['Default', 'image'];
        }

        if (
            Media::VIDEO === $form->get('media_type')->getData()
            && (null === $form->get('video_url')->getData()
                || !$this->videoHelper->checkUrl($form->get('video_url')->getData()))
        ) {
            return ['Default', 'video'];
        }

        return ['Default'];
    }
}
