<?php

namespace App\Form;

use App\Entity\Professeur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfesseurProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('date_naissance', BirthdayType::class)
            ->add('profile_picture_file', VichImageType::class, [
                "label" => "Votre image de profil",
                'required' => false,
                'imagine_pattern' => 'my_fixed_filter'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Professeur::class,
        ]);
    }
}
