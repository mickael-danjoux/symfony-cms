<?php

namespace App\Form\User;

use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('displayName',TextType::class,[
                'label' => 'Nom'
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email'
            ])
            ->add('plain_password', PasswordType::class,[
                'label' => 'Mot de passe',
                'help' => '<i class="fas fa-exclamation-triangle"></i> Remplir ce champ réinitialisera le mdp actuel.',
                'help_html' => true,
                'required' => false,
                'mapped' => false
            ])
            ->add('roles',ChoiceType::class,[
                'label' => 'Role',
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN',
                ],
                'mapped' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
