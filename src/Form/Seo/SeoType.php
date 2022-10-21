<?php

namespace App\Form\Seo;

use App\Entity\Seo\Seo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Balise Titre',
                'required' => false
            ])
            ->add('description', TextType::class, [
                'label' => 'Balise Description',
                'required' => false
            ])
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Seo::class
        ]);
    }

}
