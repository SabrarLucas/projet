<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Products;
use App\Entity\Suppliers;
use App\Repository\CategoriesRepository;
use App\Repository\SuppliersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options:[
                'label' => 'Nom'
            ])
            ->add('description')
            ->add('price', options:[
                'label' => 'Prix'
            ])
            ->add('image')
            ->add('stock', options:[
                'label' => 'Unités en stock'
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'group_by' => 'parent.name',
                'query_builder' => function(CategoriesRepository $cr)
                {
                    return $cr->createQueryBuilder('c')
                        ->where('c.parent IS NOT NULL')
                        ->ORDERbY('c.name', 'ASC');
                }
            ])
            ->add('supp_id', EntityType::class, [
                'class' => Suppliers::class,
                'choice_label' => 'name',
                'label' => 'Fournisseur',
                'query_builder' => function(SuppliersRepository $sr)
                {
                    return $sr->createQueryBuilder('s')
                        ->ORDERbY('s.name', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
