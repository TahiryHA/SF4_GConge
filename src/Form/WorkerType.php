<?php

namespace App\Form;

use App\Entity\Worker;
use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('societe',EntityType::class,[
                'class' => Societe::class,
                'choice_label' => 'name',
            ])
            ->add('name',TextType::class,[
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('email',EmailType::class,[
                'attr' => ['placeholder' => 'exemple@serveur.com']
            ])
            ->add('tel', TextType::class,[
                'label' => 'Téléphone',
                'attr' => [
                    'placeholder' => 'Numéro de téléphone'
                ]
            ])

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
