<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Stage;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire'])
                ]
            ])
            ->add('typestage', ChoiceType::class, [
                'choices' => [
                    'PFE' => 'PFE',
                    'Été' => 'Été',
                    'Technicien' => 'Technicien',
                    'Ouvrier' => 'Ouvrier'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le type de stage est obligatoire'])
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire'])
                ]
            ])
            ->add('duree', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La durée est obligatoire']),
                    new Range(['min' => 1, 'max' => 12, 'minMessage' => 'La durée doit être d\'au moins {{ limit }} mois', 'maxMessage' => 'La durée ne peut pas dépasser {{ limit }} mois'])
                ]
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'nom',
                'constraints' => [
                    new NotBlank(['message' => 'Le département est obligatoire'])
                ]
            ])
            ->add('encadrant', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}