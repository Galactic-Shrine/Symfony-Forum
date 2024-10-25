<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'translation_domain' => 'Category',
            ])
            ->add('Description', CollectionType::class, [
                'entry_type' => TextareaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'translation_domain' => 'Category',
            ])
            ->add('Slug', TextType::class, [
                'translation_domain' => 'Category',
            ])
            ->add('Controllers', CollectionType::class, [
                'entry_type' => TextType::class,
                'required' => true,
                'translation_domain' => 'Category',
            ])
            ->add('Icon', TextType::class, [
                'required' => false,
                'translation_domain' => 'Category',
            ])
            ->add('Banner', TextType::class, [
                'required' => false,
                'translation_domain' => 'Category',
            ])
            ->add('CategoryImage', TextType::class, [
                'label' => 'Category.Image',
                'required' => false,
                'translation_domain' => 'Category',
            ])
            ->add('Position', TextType::class, [
                'translation_domain' => 'Category',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
