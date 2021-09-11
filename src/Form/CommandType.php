<?php

namespace App\Form;

use App\Entity\Command;
use App\Entity\Status;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CommandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('orderDate')
            ->add('ClickAndCollect')
            ->add('status',EntityType::class,[
                'class'=>Status::class,
                'choice_label'=>'information'
            ])
            ->add('client',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'lastname'
            ])
            ->add('payment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
        ]);
    }
}
