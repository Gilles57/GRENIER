<?php

namespace App\Form;

use App\Entity\Oeuvre;
use App\Entity\Visite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('date')
            ->add('description')
            ->add('lieu')
            ->add('oeuvre', EntityType::class,
                ['class' => Oeuvre::class,
                    'label' => "Œuvres",
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                    'mapped' => false,
                    'required' => false,
                ])
            ->add('media', FileType::class,
                [
                    'label' => "Illustration",
                    'multiple' => false,
                    'mapped' => false,
                    'required' => false,
                ])
             ->add('photos', FileType::class,
            [
                'label'=> false,
                'multiple' => true,
                'mapped'=> false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visite::class,
        ]);
    }
}
