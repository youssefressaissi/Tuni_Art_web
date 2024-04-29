<?php

namespace App\Form;

use App\Entity\Gallery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('galleryName')
            ->add('galleryDescription')
            ->add('galleryLocation', ChoiceType::class, [
                'choices' => [
                    'Ariana' => 'Ariana',
                    'Beja' => 'Beja',
                    'Ben Arous' => 'Ben Arous',
                    'Bizerte' => 'Bizerte',
                    'Gabes' => 'Gabes',
                    'Gafsa' => 'Gafsa',
                    'Jendouba' => 'Jendouba',
                    'Kairouan' => 'Kairouan',
                    'Kasserine' => 'Kasserine',
                    'Kebili' => 'Kebili',
                    'Kef' => 'Kef',
                    'Mahdia' => 'Mahdia',
                    'Manouba' => 'Manouba',
                    'Medenine' => 'Medenine',
                    'Monastir' => 'Monastir',
                    'Nabeul' => 'Nabeul',
                    'Sfax' => 'Sfax',
                    'Sidi Bouzid' => 'Sidi Bouzid',
                    'Siliana' => 'Siliana',
                    'Sousse' => 'Sousse',
                    'Tataouine' => 'Tataouine',
                    'Tozeur' => 'Tozeur',
                    'Tunis' => 'Tunis',
                    'Zaghouan' => 'Zaghouan',
                ],
                'placeholder' => 'Select a state', // Optional placeholder
            ])
            ->add('galleryTel')
            ->add('operatingHours')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}
