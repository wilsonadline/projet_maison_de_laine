<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\TypeCategories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('typeCategories', EntityType::class, [
                'class'=> TypeCategories::class,
                'label'=> 'Type de catégories',
                'choice_label'=> 'nom',
                // 'mapped'=>false
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Choisissez une image'
            ])


            // ->add('createdAt')
            // ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
