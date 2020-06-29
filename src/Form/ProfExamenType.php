<?php

namespace App\Form;

use App\Entity\Examen;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfExamenType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut')
            ->add('fin')
            ->add('devoirs')
            ->add('statut')
            ->add('coefficient')
            ->add('note_max')
            ->add('salle')
            ->add('matiere')
            ->add('professeur')
            ->add('groupe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Examen::class,
        ]);
    }
}
