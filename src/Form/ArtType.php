<?php

namespace App\Form;

use App\Entity\Art;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('imageId', FileType::class, [
                'label' => 'Image  (Image file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ])
            
            ->add('musicPath', FileType::class, [
                'label' => 'Music (Audio file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '50M', // Adjust the maximum file size as needed
                        'mimeTypes' => [
                            'audio/mpeg', // MP3 files
                            'audio/x-wav', // WAV files
                            'audio/wav',   // Alternative WAV MIME type
                            'audio/x-aac', // AAC files
                            // Add more audio MIME types if needed
                        ],
                        'mimeTypesMessage' => 'Please upload a valid audio file',
                    ])
                ],
                ])
            
            ->add('artist_id')
            ->add('drawing_data', TextType::class, [
                'mapped' => false, // This field is not mapped to any property of the entity
            ]);
            
           
            ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Art::class,
        ]);
    }
}
