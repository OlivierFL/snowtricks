<?php

namespace App\Form\EventListener;

use App\Entity\TricksMedia;
use App\Repository\TricksMediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddCoverImageFieldListener implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'onPostSetData',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onPostSetData(FormEvent $event): void
    {
        $trick = $event->getData();
        $event->getForm()->add('cover_image', EntityType::class, [
            'class' => TricksMedia::class,
            'mapped' => false,
            'placeholder' => false,
            'required' => false,
            'label' => 'Select an existing image',
            'choice_label' => function (TricksMedia $tricksMedia) {
                return $tricksMedia->getMedia()->getUrl();
            },
            'query_builder' => function (TricksMediaRepository $repository) use ($trick) {
                return $repository->createCoverImageBuilder($trick);
            },
            'expanded' => true,
            'multiple' => false,
        ])
        ;
    }
}
