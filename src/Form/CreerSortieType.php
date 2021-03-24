<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use phpDocumentor\Reflection\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateDebut', DateTimeType::class)
            ->add('duree', IntegerType::class)
            ->add('dateCloture', DateTimeType::class)
            ->add('nbInscriptionsMax', IntegerType::class)
            ->add('descriptionInfos', TextareaType::class)
            ->add('site' , EntityType::Class , [
                    'class' => Site::class,
                    'choice_label' => 'libelle',
                    'placeholder' => 'Selectionnez un site'
                ]
            )
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'rue',
                'placeholder' => 'Sélectionnez la rue'
                ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
