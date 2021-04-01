<?php

namespace App\Form;

use App\Entity\Trick;
use App\Form\EventListener\AddCoverImageFieldListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CoverImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_cover_image', ImageType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Add a new cover image',
            ])
            ->add('altText', TextType::class, [
                'label' => 'Alternative text',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'The cover image "alt text" field can not be blank',
                        'groups' => ['image'],
                    ]),
                ],
            ])
            ->addEventSubscriber(new AddCoverImageFieldListener())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
            'validation_groups' => function (FormInterface $form) {
                $altText = $form->get('altText')->getData();
                $imageFile = $form->get('new_cover_image')->get('image')->getData();
                if ((null === $imageFile && null !== $altText) || (null === $altText && null !== $imageFile)) {
                    return ['Default', 'image'];
                }

                return ['Default'];
            },
        ]);
    }
}
