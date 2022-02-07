<?php
	
	namespace App\Controller\App;
	
	use App\Entity\Customer;
	use App\Entity\User;
	use App\Form\RegistrationType;
	use App\Services\AccountActivation;
	use Doctrine\ORM\EntityManagerInterface;
	use Psr\Log\LoggerInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\Config\Definition\Exception\Exception;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
	use Symfony\Component\Routing\Annotation\Route;
	
	class RegistrationController extends AbstractController
	{
		public function __construct(private EntityManagerInterface $em, private LoggerInterface $logger)
		{}
		
		#[Route('/inscription', name: "app_registration")]
		public function registration(Request $request, UserPasswordHasherInterface $hasher, AccountActivation $accountActivation)
		{
			$customer = new Customer();
			
			$form = $this->createForm(RegistrationType::class, $customer);
			$form->handleRequest($request);
			
			if ($form->isSubmitted() && $form->isValid()) {
				try {
					$customer->setPassword(
						$hasher->hashPassword(
							$customer,
							$form->get('password')->getData()
						)
					);
					$customer->setDisplayName($customer->getFirstName() . ' ' . $customer->getLastName());
					
					$accountActivation->sendMail($customer);
					
					$this->em->persist($customer);
					$this->em->flush();
					$this->addFlash('success', 'Confirmez votre adresse email pour finaliser votre inscription');
					
					return $this->redirectToRoute('app_index');
				} catch (\Exception $e) {
					$this->logger->error('ERREUR CREATION COMPTE :' . $e->getMessage());
					$this->addFlash('danger', 'Une erreur est survenue. Veuillez réessayer ultérieurement.');
				}
			}
			
			return $this->render('app/registration/registration.html.twig', [
				'form' => $form->createView()
			]);
		}
		
		#[Route('/validation-email/{token}', name: "app_registration_email_verify")]
		public function emailVerify(?User $user)
		{
			if (!$user instanceof User || new \DateTime() > $user->getTokenDisabledAt()) {
				$this->addFlash('danger', 'Une erreur est survenue.');
				return $this->redirectToRoute('app_index');
			}
			
			try {
				$user->setToken(null);
				$user->setTokenDisabledAt(null);
				$user->setIsVerified(true);
				$this->em->flush();
			} catch (Exception $e) {
				throw new Exception($e->getMessage(), 500);
			}
			
			$this->addFlash('success', 'Votre compte a bien été validé !');
			return $this->redirectToRoute('app_index');
		}
	}