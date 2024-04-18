<?php

namespace App\Form;

use App\Entity\Auction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class AuctionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('auctionName')
            ->add('startDate')
            ->add('endDate')
            ->add('threshold')
            ->add('status')
            //->add('highestBid')
            //->add('currentwinnerId')
         //   ->add('interactions')
            ->add('artRef')
            //->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auction::class,
        ]);
    }
}
