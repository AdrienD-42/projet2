<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Site;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
                [
                    'label' => 'Nom',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez votre nom',
                        ]),
                        new Length([
                            'min' => 2,
                            'minMessage' => 'votre nom doit avoir au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 100,
                        ]),
                                        ]
                ])
            ->add('prenom', TextType::class,
                [
                    'label' => 'Prenom',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez votre prenom',
                        ]),
                        new Length([
                            'min' => 2,
                            'minMessage' => 'votre prenom au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                            'max' => 100,
                        ]),
                    ]
                ])

            ->add('email', TextType::class,
                [
                    'label' => 'Email',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Indiquer un mail!',
                        ]),
                        new Length([
                            // max length allowed by Symfony for security reasons
                            'max' => 180,
                        ]),
                    ]
                ])

            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez indiquer un mot de passe',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'votre mot de passe doit avoir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
