<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('image', FileType::class, [
            'mapped' => false,
            'required' => false,
            'label' => $options['label'],
            'help' => 'Please select an image file',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please select an image file',
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => 'Add an image',
        ]);
    }
}
