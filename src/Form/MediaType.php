<?php

namespace App\Form;

use App\Entity\Media;
use App\Form\EventListener\AddAltTextFieldListener;
use App\Form\EventListener\AddImageFieldListener;
use App\Form\EventListener\AddTypeFieldListener;
use App\Form\EventListener\AddVideoUrlFieldListener;
use App\Service\VideoHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->addEventSubscriber(new AddTypeFieldListener())
            ->addEventSubscriber(new AddImageFieldListener())
            ->addEventSubscriber(new AddVideoUrlFieldListener())
            ->addEventSubscriber(new AddAltTextFieldListener())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'new' => true,
            'validation_groups' => function (FormInterface $form) {
                if ($form->getConfig()->getOption('new')) {
                    return $this->addNewMediaFormConstraints($form);
                }

                return $this->addMediaFormConstraints($form);
            },
        ]);
    }

    /**
     * @param FormInterface $form
     *
     * @return string[]
     */
    private function addNewMediaFormConstraints(FormInterface $form): array
    {
        $type = $form->get('type')->getData();

        if (
            Media::IMAGE === $type
        ) {
            return ['Default', 'image'];
        }

        if (
            Media::VIDEO === $type
            && (null === $form->get('video_url')->getData()
                || !$this->videoHelper->checkUrl($form->get('video_url')->getData()))
        ) {
            return ['Default', 'video'];
        }

        return ['Default'];
    }

    /**
     * @param FormInterface $form
     *
     * @return string[]
     */
    private function addMediaFormConstraints(FormInterface $form): array
    {
        $type = $form->getData()->getType();

        if (Media::IMAGE === $type) {
            return ['Default', 'image'];
        }

        if (Media::YOUTUBE_VIDEO === $type || Media::VIMEO_VIDEO === $type) {
            return ['Default', 'video'];
        }

        return ['Default'];
    }
}
