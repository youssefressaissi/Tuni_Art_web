<?php

namespace App\Form;

use App\Entity\Art;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artTitle')
            ->add('artPrice')
            ->add('type')
            ->add('creation')
            ->add('description')
            ->add('style')
            ->add('imageId')
            ->add('musicPath')
            ->add('artViews')
            ->add('isavailable')
            ->add('artist')
            ->add('cart')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Art::class,
        ]);
    }
}
