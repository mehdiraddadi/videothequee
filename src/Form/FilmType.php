<?php

namespace App\Form;

use App\Entity\Film;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('categoryFilm')
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
            'csrf_protection' => false
        ]);
    }

    public function getName()
    {
        return 'film';
    }
}