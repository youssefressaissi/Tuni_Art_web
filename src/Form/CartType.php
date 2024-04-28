<?php

namespace App\Form;

use App\Entity\Cart;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Regex;


class CartType extends AbstractType
{
   public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uid', TextType::class, [
                'constraints' => [
                    new NotBlank(), // Ensure the UID is not blank
                    new Length(['min' => 1, 'max' => 255]), // Validate the length of the UID
                    new GreaterThan(0),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'The UID must be an integer.'
                    ]),
                    // Add more constraints as needed
                ],
            ])
            ->add('art_ref', TextType::class, [
                'constraints' => [
                    new NotBlank(), // Ensure the art_ref is not blank
                    new Length(['min' => 1, 'max' => 255]), // Validate the length of the art_ref
                    new GreaterThan(0),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'The art_ref must be an integer.'
                    ]),
                    // Add more constraints as needed
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cart::class,
        ]);
    }
}
