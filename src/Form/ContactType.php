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
                    'placeholder' => 'Nom',
                    'minlength' => 3,
                    'maxlength' => 10
                ]
            ])
            ->add('prenom', TextType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Prénom',
                    'minlength' => 3,
                    'maxlength' => 10
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Email',
                ]
            ])
            ->add('sujet', TextType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Sujet',
                    'minlength' => 3,
                    'maxlength' => 50
                ]
            ])
            ->add('message', TextareaType::class, [
                'label'=> ' ',
                'attr' => [
                    'placeholder' => 'Message',
                    'minlength' => 3,
                    'maxlength' => 2000
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
