<?php

namespace App\Form;

use App\Entity\Absence;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsenceEleveType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statut', CheckboxType::class, [
                    "disabled" => true,
                    "label" => "Absence justifiée"
                ]
            )
            ->add('motif', CKEditorType::class, [
                "label" => "Justification de l'absence",
                'config' => array('toolbar' => 'basic'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Absence::class,
        ]);
    }
}
