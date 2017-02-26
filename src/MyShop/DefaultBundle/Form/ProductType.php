<?php

namespace MyShop\DefaultBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
                'label' => 'Модель товара'
            ])
            ->add('price', NumberType::class, [
                "label" => 'Цена товара'
            ])
            ->add('description', TextareaType::class, [
                'label' => "Описание товара"
            ])
            ->add('comment', TextareaType::class, [
                'label' => "Комментарий товара"
            ])
            ->add('category', EntityType::class, [
                "class" => "MyShopDefaultBundle:Category", // Варианты выпадающего списка
                "choice_label" => "name",   // Выпадающий список
                "label" => "Категория" // название перед выпадающим списком
            ])
            
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyShop\DefaultBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myshop_defaultbundle_product';
    }


}
