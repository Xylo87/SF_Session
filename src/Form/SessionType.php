<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formateur;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom :'
            ])
            ->add('nbPlaces', IntegerType::class, [
                'label' => 'Nombre de places :',
                'attr' => [
                    'min' => 1,
                    'max' => 30
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début :'
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin :'
            ])
            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'label' => 'Formation :',
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('f')
                          ->orderBy('f.nom', 'ASC');
            }
                // 'choice_label' => 'id',
            ])
            ->add('formateur', EntityType::class, [
                'class' => Formateur::class,
                'label' => 'Formateur.rice référent.e :',
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('f')
                          ->orderBy('f.nom', 'ASC');
            }
                // 'choice_label' => 'id',
            ])
            ->add('stagiaires', EntityType::class, [
                'class' => Stagiaire::class,
                'label' => 'Stagiaires :',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('s')
                          ->orderBy('s.nom', 'ASC');
            }
                // 'choice_label' => 'id',
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
            'data_class' => Session::class,
        ]);
    }
}
