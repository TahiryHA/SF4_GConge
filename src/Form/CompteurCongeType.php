<?php

namespace App\Form;

use App\Entity\Type;
use App\Entity\CompteurConge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteurCongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('gestionconges')
            ->add('type_id',EntityType::class,[
                'label' => 'Type',
                'class' => Type::class,
                'choice_label' => 'name',
                'mapped' => false
            ])
            ->add('acquis')
            ->add('restant')
            ->add('attente')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CompteurConge::class,
        ]);
    }
}
