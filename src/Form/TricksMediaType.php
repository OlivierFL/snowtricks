<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\TricksMedia;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('media', MediaType::class, [
            'data_class' => Media::class,
            'label' => false,
            'new' => ['new' => $options['new']],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TricksMedia::class,
            'new' => false,
        ]);
    }
}
