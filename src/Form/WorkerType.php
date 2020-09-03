<?php

namespace App\Form;

use App\Entity\Worker;
use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('societe',EntityType::class,[
                'class' => Societe::class,
                'choice_label' => 'name',
            ])
            ->add('lastname')
            ->add('email')
            ->add('tel')

            // ->add('conge')
            // ->add('compteurConge_id')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Worker::class,
        ]);
    }
}
