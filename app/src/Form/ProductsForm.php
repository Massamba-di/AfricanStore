<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Commands;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProductsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price',NumberType::class,[
                'label'=> 'Prix(€)',
                'scale'=> 2,
            ])
            ->add('stock',IntegerType::class,)
            ->add('pictures',FileType::class,[
                'label' => 'Image du produit',
                'mapped' => false,
                 'constraints' => [
                    new Image(
                        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
                        mimeTypesMessage: 'seuls les formats jpeg, png et webp sont autorisés',
                    )
                ]

            ])
            ->add('size')
            #->add('commands', EntityType::class, [
               # 'class' => Commands::class,
                #'choice_label' => 'name',
                #'multiple' => true,
           # ])
          ->add('categories', EntityType::class, [
              'class' => Categories::class,
              'choice_label' => 'name',
              'placeholder' => 'Choisis-une-catégorie',

              'multiple' => true,
              'expanded' => false,
          ])

            ->add('ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
