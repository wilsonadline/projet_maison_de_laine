<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('prenom',TextType::class ,[
                'label' => 'Prénom'
            ])
            ->add('tel', NumberType::class,[
                'label' => 'Numéro de téléphone'
            ])
            ->add('adresse', TextType::class,[
                'label' => 'Adresse'
            ])
            ->add('cp', NumberType::class,[
                'label' => 'Code postal',
            ])
            ->add('ville', TextType::class,[
                'label' => 'Ville'
            ])
            ->add('Suivant', SubmitType::class,[
                'attr' => ['class' => 'btn btn-sm border border-dark']
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
