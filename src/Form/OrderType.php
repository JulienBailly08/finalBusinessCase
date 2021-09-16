<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      
        $user = $options['user'];
        $builder
            ->add('adresses', EntityType::class,[
                'label'=>'Confirmer votre adresse',
                'required'=> true,
                'class'=> Adress::class,
                'choices'=>$user->getAdresses(),
                'multiple'=>false,
                'expanded'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user'=> array(),
        ]);
    }
}
