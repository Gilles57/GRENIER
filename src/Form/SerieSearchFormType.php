<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('code', null, [
//                'required' => false,
//                'label' => false,
//                'attr' => [
//                    'placeholder' => 'Saisir un code'
//                ]
//            ])
            ->add('titre', null, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisir un titre'
                ]
            ])
            ->add('anneeSortie', null, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Année de sortie'
                ]
            ])
//            ->add('genres', EntityType::class, [
//                'class' => Genre::class,
//                'choice_label' => 'nom',
////                'expanded' => false,
//                'multiple' => true,
//                'required' => false,
//                'label' => false,
//            ])
//            ->add('vu', ChoiceType::class, [
//                'choices' => [
//                    'Oui' => true,
//                    'Non' => false,
//                    '?' => null,
//                ],
//                    'expanded' => false,
//                    'multiple' => false,
//                    'required' => false,
//                    'label' => false,
//
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
            'method' => 'get',
            'csrf_protection' => false,
        ]);
    }

//    public function getBlockPrefix()
//    {
//        return'';
//    }
}
