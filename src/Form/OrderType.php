<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('order_date', DateType::class, [
            'widget' => 'single_text', // Display as a single text input
            'format' => 'yyyy-MM-dd', // Define the desired date format
            'constraints' => [
                new GreaterThanOrEqual('today'), // Ensure orderDate is greater than or equal to today
            ],
        ])
            ->add('totalPrice', TextType::class, [
                'constraints' => [
                    new NotBlank(), // Ensure the UID is not blank
                    new Length(['min' => 1]), // Validate the length of the UID
                    new GreaterThan(0),
                    // Add more constraints as needed
                ],
            ]) 
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Not Sold' => false,
                    'Sold' => true, 
                ],
                'placeholder' => 'Choose an option', // Optional placeholder
            ])

                        ->add('uid', TextType::class, [
                'constraints' => [
                    new NotBlank(), // Ensure the UID is not blank
                    new Length(['min' => 1, 'max' => 255]), // Validate the length of the UID
                    new GreaterThan(0),
                    // Add more constraints as needed
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
