<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Demandestage;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('fichier')
            ->add('demandestage', EntityType::class, [
                'class' => Demandestage::class,
                'choice_label' => fn (Demandestage $d) => sprintf('#%d - %s', $d->getId(), $d->getStage()?->getTitre() ?? ''),
                'query_builder' => function ($repo) use ($options): QueryBuilder {
                    $qb = $repo->createQueryBuilder('d')
                        ->orderBy('d.id', 'DESC');

                    if ($options['user'] instanceof User) {
                        $qb->andWhere('d.etudiant = :u')->setParameter('u', $options['user']);
                    }

                    return $qb;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'user' => null,
        ]);

        $resolver->setAllowedTypes('user', ['null', User::class]);
    }
}
