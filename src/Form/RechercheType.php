<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', SearchType::class, [
                'label'=>false,
                'attr' =>[
                    'class' => 'form-control',
                    'placeholder'=>'Mot-clés'
                ],
                'required' => false
            ])
            ->add('article', EntityType::class, [
                'class' => Articles::class,
                'label' => false,
                'attr' =>[
                    'class' =>'form-control'
                ],
                'required' => false
            ])
            ->add('Rechercher', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
