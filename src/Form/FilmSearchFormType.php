<?php

namespace App\Form;

use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\FilmSearch;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Saisir un titre'
                ]
            ])
            ->add('annee', null, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Année de sortie'
                ]
            ]);
    }
}