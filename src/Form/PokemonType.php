<?php

namespace App\Form;

use App\Entity\Pokemon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', HiddenType::class)
            ->add('type1', HiddenType::class)
            ->add('type2', HiddenType::class)
            ->add('spriteUrl', HiddenType::class)
            ->add('answer', TextType::class, [
                'label' => 'Your guess',
                'mapped' => false,
                'constraints' => [
                    new Length(['min' => 1, 'max' => 255]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'pokemonType';
    }
}
