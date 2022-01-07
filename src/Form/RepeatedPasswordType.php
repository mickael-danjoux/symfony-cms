<?php
	
	namespace App\Form;
	
	use Symfony\Component\Form\AbstractType;
	use Symfony\Component\Form\Extension\Core\Type\PasswordType;
	use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
	use Symfony\Component\Form\FormBuilderInterface;
	use Symfony\Component\OptionsResolver\OptionsResolver;
	use Symfony\Component\Validator\Constraints\Length;
	use Symfony\Component\Validator\Constraints\NotBlank;
	
	class RepeatedPasswordType extends AbstractType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			$builder
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
						'label' => false
					],
					'second_options' => [
						'label' => false,
					],
					'invalid_message' => 'Les champs mot de passe doivent être identique',
					// Instead of being set onto the object directly,
					// this is read and encoded in the controller
					'mapped' => false,
				])
			;
		}
		
		public function configureOptions(OptionsResolver $resolver)
		{
			$resolver->setDefaults([]);
		}
	}
