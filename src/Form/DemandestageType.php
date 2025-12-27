<?php

namespace App\Form;

use App\Entity\Demandestage;
use App\Entity\Stage;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DemandestageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $includeEtudiant = (bool) ($options['include_etudiant'] ?? false);
        $includeStatut = (bool) ($options['include_statut'] ?? true);
        $includeDate = (bool) ($options['include_date'] ?? true);
        $includeFollowup = (bool) ($options['include_followup'] ?? false);
        $includeEvaluation = (bool) ($options['include_evaluation'] ?? false);
        $includeFinStage = (bool) ($options['include_fin_stage'] ?? false);

        $builder
            ->add('stage', EntityType::class, [
                'class' => Stage::class,
                'choice_label' => 'titre',
                'constraints' => [
                    new NotBlank(['message' => 'Le stage est obligatoire'])
                ]
            ])
        ;

        if ($includeEtudiant) {
            $builder->add('etudiant', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'constraints' => [
                    new NotBlank(['message' => 'L\'étudiant est obligatoire'])
                ]
            ]);
        }

        if ($includeStatut) {
            $builder->add('statut', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Acceptée' => 'Acceptée',
                    'Refusée' => 'Refusée'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le statut est obligatoire'])
                ]
            ]);
        }

        if ($includeDate) {
            $builder->add('datedemande', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de demande est obligatoire'])
                ]
            ]);
        }

        if ($includeFollowup) {
            $builder->add('avisEncadrant', TextareaType::class, [
                'required' => false,
            ]);
        }

        if ($includeFinStage) {
            $builder->add('valideFinStage', CheckboxType::class, [
                'required' => false,
            ]);
        }

        if ($includeEvaluation) {
            $builder
                ->add('note', IntegerType::class, [
                    'required' => false,
                ])
                ->add('appreciation', TextareaType::class, [
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demandestage::class,
            'include_etudiant' => false,
            'include_statut' => true,
            'include_date' => true,
            'include_followup' => false,
            'include_evaluation' => false,
            'include_fin_stage' => false,
        ]);
    }
}
