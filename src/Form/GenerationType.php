<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenerationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gen1', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen2', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen3', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen4', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen5', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen6', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen7', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen8', CheckboxType::class, [
                'required' => false
            ])
            ->add('gen9', CheckboxType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
