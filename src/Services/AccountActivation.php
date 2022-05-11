<?php
	
	
	namespace App\Services;
	
	use App\Entity\User\Customer;
    use Exception;
    use Psr\Log\LoggerInterface;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

    class AccountActivation
	{
		public function __construct(
			private ContainerInterface $container,
			private MailerInterface $mailer,
			private UrlGeneratorInterface $urlGenerator,
			private LoggerInterface $logger
		){}
		
		
		/**
		 * @throws Exception
		 * @throws TransportExceptionInterface
		 */
		public function sendMail(Customer $customer)
		{
			try {
				$customer->setToken(uniqid());
				
				$tokenExpiration = new \DateTime("+ 1 week");
				$customer->setTokenDisabledAt($tokenExpiration);
				
				$urlValidation = $this->urlGenerator->generate('app_registration_email_verify', ['token' => $customer->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
				
				$email = (new TemplatedEmail())
					->to($customer->getEmail())
					->subject('Vérification email')
					->htmlTemplate('emails/email_verify.html.twig')
					->context([
						'site_name' => $this->container->getParameter('app.site.name'),
						'urlValidation' => $urlValidation,
					])
				;
				$this->mailer->send($email);
				
			} catch (Exception $e) {
				$this->logger->error('ERREUR ENVOIE EMAIL ACTIVATION COMPTE : ' . $e->getMessage());
				throw $e;
			}
			
			
		}
	}