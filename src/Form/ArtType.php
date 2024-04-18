<?php

namespace App\Form;

use App\Entity\Art;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ArtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artTitle')
            ->add('artPrice')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Painting' => 'Painting',
                    'Sculpture' => 'Sculpture',
                    'Photography' => 'Photography',
                    'Drawing' => 'Drawing',
                    'Digital Art' => 'Digital_art',
                    'Music' => 'Music',
                ],
                'placeholder' => 'Choose a type',
                'label' => 'Type',
                'required' => false, // Make the type field not required initially
            ])
            ->add('style', ChoiceType::class, [
                'choices' => ['Realism' => 'realism',
                'Impressionism' => 'impressionism',
                'Classical' => 'classical',
                    'Jazz' => 'jazz',
                    'Landscape' => 'landscape',
                    'Portrait' => 'portrait',
                    'Charcoal' => 'charcoal',
                    'Pen and Ink' => 'pen_and_ink',
                    'Pixel Art' => 'pixel_art',
                    'Vector Art' => 'vector_art',
                    
                ], // Initially empty
                'placeholder' => 'Choose a style',
                'label' => 'Style',
                'required' => false, // Make the style field not required initially
            ])
            ->add('creation')
            ->add('description')
            ->add('imageId')
            ->add('musicPath')
            //->add('artViews')
            ->add('artist_id')
            //->add('isavailable')
           
            ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Art::class,
        ]);
    }
}
