<?php

namespace App\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                    new Image([
                        'maxSize' => '2M',
                        'mimeTypesMessage' => 'Please upload a valid image file',
                        'groups' => ['image'],
                    ]),
                ],
            ]);
        }
    }
}
