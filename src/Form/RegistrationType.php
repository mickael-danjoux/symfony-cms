<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
				'label' => 'Adresse Email'
			])
            ->add('firstName', TextType::class, [
				'label' => 'Prénom'
			])
            ->add('lastName', TextType::class, [
				'label' => 'Nom'
			])
            ->add('phone', TextType::class, [
				'label' => 'Téléphone'
			])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'first_options' => [
					'constraints' => [
						new NotBlank([
							'message' => 'Veuillez renseigner un mot de passe',
						]),
						new Length([
							'min' => 8,
							'minMessage' => 'Votre mot de passe doit faire au moins {{ limit }} caractères',
							// max length allowed by Symfony for security reasons
							'max' => 4096,
						]),
					],
					'label' => 'Mot de passe',
					'required' => true
				],
				'second_options' => [
					'label' => 'Confirmation mot de passe',
					'required' => true
				],
				'invalid_message' => 'Les champs mot de passe doivent être identique',
				// Instead of being set onto the object directly,
				// this is read and encoded in the controller
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
