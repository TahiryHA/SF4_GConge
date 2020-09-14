<?php

namespace App\Form;

use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => false
            ])
            ->add('name', TextType::class, [
                'label' => false
            ])
            ->add('lastname', TextType::class, [
                'label' => false
            ])
            ->add('service', EntityType::class, [
                'label' => false,
                'class' => Service::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control select2bs4'
                ]
            ])
            ->add('role', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Employée' => 'ROLE_EMPLOYER'
                ],
                'attr' => [
                    'class' => 'form-control select2bs4'
                ],
                'mapped' => false
            ])
            ->add('email', EmailType::class, [
                'label' => false
            ])
            ->add('tel', TextType::class, [
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
