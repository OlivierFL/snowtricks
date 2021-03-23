<?php

namespace App\Form\EventListener;

use App\Entity\Media;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddTypeFieldListener implements EventSubscriberInterface
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
        $form = $event->getForm();
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
    }
}
