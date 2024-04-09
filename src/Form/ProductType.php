<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Model;
use App\Entity\Product;
use App\Entity\Size;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('price')
            ->add('description')
            ->add('quantity')
            ->add('size', EntityType::class, [
                'class' => Size::class,
                'choice_label' => 'name',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
            ])
            ->add('color', EntityType::class, [
                'class' => Color::class,
                'choice_label' => 'name',
            ])

            //  ->add('model', EntityType::class, [
            //      'class' => Model::class,
            //      'choice-label' => 'name', //
            //  ])

            //->add(
            //    $builder->create('media', FormType::class, ['by_reference' => true])
            //        ->add('src', TextType::class)
            //)
            ->add('media', FileType::class, [
                'required' => false,
                'label' => "Ajouter une image",
                'mapped' => false
            //     'data_class' => null
            ])
            // ->add('media', CollectionType::class,[
            // //     'entry_type' => MediaType::class,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
