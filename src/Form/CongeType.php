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
use App\Repository\GestionCongeRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CongeType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    


        $builder
            // ->add('societe',EntityType::class,[
            //     'label' => 'Service',
            //     'class' => Societe::class,
            //     'choice_label' => 'name',
            //     'attr' => [
            //         'class' => 'form-control select2bs4',
            //         'data-dropdown-css-class' => 'select2bs4',
            //         'style' =>  '"width: 100%;'
            //     ],
            //     'mapped' => false
            // ])
            // ->add('workers',EntityType::class,[
            //     'label' => 'Employée',
            //     'class' => Worker::class,
            //     'choice_label' => 'name',
            //     'placeholder' => 'Trouver l\'employée',

            //     // 'attr' => [
            //     //     'class' => 'form-control select2bs4',
            //     //     'data-dropdown-css-class' => 'select2bs4',
            //     //     'style' =>  '"width: 100%;'
            //     // ],
            //     'mapped' => false
            // ])
            ->add('date_demande', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime()
            ])
            ->add('motif',ChoiceType::class,[
                'label' => $this->security->getUser()->getUsername(),
                'choices' => $this->test(),
                // 'choice_label' => 'name',
                'placeholder' => 'Choisissez la motif du congé',
                'multiple' => false,
            ])
            ->add('date_deb', DateType::class, [
                'label' => 'Début',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('date_fin', DateType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            
            // ->add('duree',TextType::class,[
            //     'label' => 'Durée'
            // ])
            ->add('qte_dispo',IntegerType::class,[
                'label' => 'Quantité disponible'
            ])
            ->add('start',ChoiceType::class,[
                'label' => 'Commence le',
                'choices' => [
                    'Le matin' => 1,
                    'L\'après-midi' => 0
                ]
            ])
            ->add('end',ChoiceType::class,[
                'label' => 'Se termine le',
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

    public function test(){
        
            $user = $this->security->getUser();

            $gc = $user->getGestionConges();
    
            $cc = [];
            foreach ($gc as $value) {
                foreach ($value->getCompteurCongeId() as $val) {
        
                    $cc [$val->getTypeId()->getName()] =  $val->getId();
                }
            }

            return $cc;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
