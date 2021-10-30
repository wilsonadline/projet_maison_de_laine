<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('errors', HiddenType::class,[
            'attr' =>[ 
                'class' => 'errors'
            ]
        ])
            ->add('Titulaire', TextType::class,[
                'attr' =>[ 
                    'class' => 'cardholder-name',
                    'placeholder'=> ' Titulaire de la carte'
                ]
            ])
            ->add('Carte'
            ) 
            
            ->add('card_errors', HiddenType::class,[
                'attr' =>[ 
                    'class' => 'card-errors',
                    'role'=>"alert"
                ]
            ])
            ->add('boutton', SubmitType::class,[
                'attr' =>[ 
                    'class'=> 'btn border border-dark card-button',
                   
                ]
            ])
        ;
    }

    // {# <form class=" mt-4" style="width:90%">
    //     <div id="errors"></div> <!-- Contiendra les messages d'erreur de paiement -->
    //     <input type="text" id="cardholder-name" placeholder="Titulaire de la carte">
    //     <div id="card-elements"></div> <!-- contiendra le formulaire de saisir des informations de carte -->
    //     <div id="card-errors" role="alert"></div> <!-- contiendra les erreurs relatives à la carte-->
    //     <button id="card-button" type="button" data-secret="{{intent.client_secret}}">Proceder au paiemenr</button>
    // </form> #}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
