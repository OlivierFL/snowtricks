<?php

namespace App\Form\EventListener;

use App\Entity\Media;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddAltTextFieldListener implements EventSubscriberInterface
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
        $media = $event->getData();
        if (
            (null !== $media && Media::IMAGE === $media->getType())
            || $form->getConfig()->getOption('new')
        ) {
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
        }
    }
}
