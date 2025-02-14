<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\File;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom :'
            ])
            ->add('dateNais', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance :'
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ville :'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email :',
                'attr' => [
                    'size' => 35
                ]
            ])
            ->add('tel', TelType::class, [
                'label' => 'Tel :'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo :',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/png',
                        'image/jpeg',
                        'image/jpg',
                        'image/gif',
                        'image/webp',
                        'image/avif'
                        ],
                        'mimeTypesMessage' => 'Please upload an image with a valid format (png, jpeg, jpg, gif, webp, avif)'
                    ])
                ],
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
