<?php

namespace App\Form;

use App\Entity\DeliveryAgency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryAgencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('agencyName')
            ->add('agencyAddress')
            ->add('nbDeliveries');
        // ->add('nbDeliveries', null, ['empty_data' => 0]); // Set default value to 0

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeliveryAgency::class,
        ]);
    }
}
