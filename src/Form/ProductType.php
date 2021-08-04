<?php

namespace App\Form;

use App\Entity\Brand;
use App\Entity\TVA;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('price')
            ->add('brand',EntityType::class,[
                'class'=>Brand::class,
                'choice_label'=>'Brand'
            ])
            ->add('description')
            ->add('picture1',FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png', 
                            'image/jpeg', 
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de charger une image valide',
                    ])
                ]

            ])
            ->add('picture2',FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png', 
                            'image/jpeg', 
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de charger une image valide',
                    ])
                ]

            ])
            ->add('picture3',FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png', 
                            'image/jpeg', 
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Merci de charger une image valide',
                    ])
                ]

            ])
            ->add('isActive')
            ->add('putInFront')
            ->add('tvaRate', EntityType::class,[
                'class'=>TVA::class,
                'choice_label'=>'rate'
            ])
            //->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
