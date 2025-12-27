<?php

namespace App\Form;

use App\Entity\Departement;
use App\Entity\Encadrant;
use App\Entity\Stage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('typeStage', ChoiceType::class, [
                'choices' => [
                    'Ouvrier' => 'ouvrier',
                    'Technicien' => 'technicien',
                    'PFE' => 'pfe'
                ]
            ])
            ->add('description', TextareaType::class, ['required' => false])
            ->add('duree')
            ->add('departement', EntityType::class, [
                'class' => Departement::class,
                'choice_label' => 'nom'
            ])
            ->add('encadrant', EntityType::class, [
                'class' => Encadrant::class,
                'choice_label' => fn($e) => $e->getNom() . ' ' . $e->getPrenom()
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}