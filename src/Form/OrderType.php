<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Shipment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $user = $options['user'];
        $builder

            ->add('shipment', EntityType::class, [
                'label' => 'Choisissez votre mode de livraison',
                'required' => true,
                'class' => Shipment::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('adresses', EntityType::class, [
                'label' => 'Confirmer votre adresse',
                'required' => true,
                'class' => Adress::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('submit',SubmitType::class, [
                'label'=>'Valider la commande',
                'attr'=>[
                    'class'=>'btn-success btn-block'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => array(),
        ]);
    }
}
