<?php

namespace App\Form;

use App\Classes\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName',TextType::class,[
                'label' => 'Nom'
            ])

            ->add('firstName',TextType::class,[
                'label' => 'Prénom'
            ])
            ->add('phone',TelType::class,[
                'label' => 'Téléphone'
            ])
            ->add('email',EmailType::class,[
                'label' => 'Email'
            ])
            ->add('message',TextareaType::class,[
                'label' => 'Message'
            ])
            ->add('company',TextType::class,[
                'label' => 'Entreprise',
                'required' => false
            ])
            ->add('address',TextType::class,[
                'label' => 'Adresse',
                'required' => false
            ])
            ->add('zipCode',TextType::class,[
                'label' => 'Code Postal',
                'required' => false
            ])
            ->add('city',TextType::class,[
                'label' => 'Ville',
                'required' => false
            ])
            ->add('agreeTerms',CheckboxType::class,[
                'label' => 'En cochant cette case, j\'accepte que mes données soient utilisées pour me recontacter',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
