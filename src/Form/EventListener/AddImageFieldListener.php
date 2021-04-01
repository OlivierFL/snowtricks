<?php

namespace App\Form\EventListener;

use App\Form\ImageType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddImageFieldListener implements EventSubscriberInterface
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
            $form->add('image', ImageType::class, [
                'mapped' => false,
                'required' => false,
            ]);
        }
    }
}
