<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\User;
use App\Entity\Conge;
use App\Entity\Worker;
use App\Entity\Societe;
use App\Entity\GestionConge;
use App\Entity\CompteurConge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societe',EntityType::class,[
                'class' => Societe::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control select2bs4',
                    'data-dropdown-css-class' => 'select2bs4',
                    'style' =>  '"width: 100%;'
                ],
                'mapped' => false
            ])
            ->add('workers',EntityType::class,[
                'class' => Worker::class,
                'choice_label' => 'name',
                'placeholder' => 'Trouver l\'employée',

                // 'attr' => [
                //     'class' => 'form-control select2bs4',
                //     'data-dropdown-css-class' => 'select2bs4',
                //     'style' =>  '"width: 100%;'
                // ],
                'mapped' => false
            ])
            ->add('date_demande', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('motif',ChoiceType::class,[
                'placeholder' => 'Choisissez la motif du conge',
                'required' => false,
                'mapped' => false
            ])
            ->add('date_deb', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('date_fin', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('date_inclus', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('duree')
            ->add('qte_dispo')
            ->add('start',ChoiceType::class,[
                'choices' => [
                    'Le matin' => 1,
                    'L\'après-midi' => 0
                ]
            ])
            ->add('end',ChoiceType::class,[
                'choices' => [
                    'Le matin' => 1,
                    'L\'après-midi' => 0
                ]
            ])
            ->add('commentaires',TextareaType::class,[
                'required' => false,
                'attr' => ['class' => 'tinymce'],
            ])
            // ->add('status')
            // ->add('date_verif')
            // ->add('users',EntityType::class,[
            //     'class' => User::class,
            //     'choice_label' => 'username'
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
