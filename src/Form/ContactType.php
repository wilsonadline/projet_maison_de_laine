<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('sujet', TextType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Sujet'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Message'
                    // 'class'=> 'mt-0'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
