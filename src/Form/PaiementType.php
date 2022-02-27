<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('errors', HiddenType::class, [
                'attr' =>[ 
                    'class' => 'errors'
                ]
            ])
            ->add('Titulaire', TextType::class, [
                'attr' =>[ 
                    'class' => 'cardholder-name',
                    'placeholder'=> ' Titulaire de la carte'
                ]
            ])
            ->add('Carte')
            ->add('card_errors', HiddenType::class, [
                'attr' =>[ 
                    'class' => 'card-errors',
                    'role'=>"alert"
                ]
            ])
            ->add('boutton', SubmitType::class, [
                'attr' =>[ 
                    'class'=> 'btn border border-dark card-button'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}