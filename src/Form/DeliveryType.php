<?php

namespace App\Form;

use App\Entity\Delivery;
use App\Entity\DeliveryAgency;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    // public function buildForm(FormBuilderInterface $builder, array $options): void
    // {
    //     $builder
    //         ->add('estimatedDate')
    //         ->add('deliveryFees')
    //         ->add('destination')
    //         ->add('state', ChoiceType::class, [
    //             'choices' => [
    //                 'Active' => true,
    //                 'Inactive' => false,
    //             ],
    //             'expanded' => true, // Render as radio buttons
    //         ])
    //         ->add('agency')
    //         ->add('order');
    // }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination')
            ->add('agency', EntityType::class, [
                'class' => DeliveryAgency::class,
                'choice_label' => 'agencyName', // Assuming 'agencyName' is the field to display
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }

















    // public function buildForm(FormBuilderInterface $builder, array $options): void
    // {
    //     $builder
    //         ->add('destination');

    //     // Check if the form should include the 'agency' field
    //     if ($options['include_agency_field']) {
    //         $builder->add('agency', EntityType::class, [
    //             'class' => DeliveryAgency::class,
    //             'choice_label' => 'agencyName', // Assuming 'agencyName' is the field to display
    //         ]);
    //     }
    // }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => Delivery::class,
    //         'include_agency_field' => true, // Default to include the 'agency' field
    //     ]);

    //     // Add any additional options here
    // }
}
