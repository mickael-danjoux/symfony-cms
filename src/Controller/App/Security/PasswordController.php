<?php

namespace App\Controller\App\Security;

use App\Entity\User;
use App\Form\RepeatedPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PasswordController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface      $em,
        private LoggerInterface             $logger,
        private UserPasswordHasherInterface $hasher
    )
    {
    }

    #[Route('/mot-de-passe-oublie', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, UserRepository $repo, MailerInterface $mailer): RedirectResponse|Response
    {
        if ($request->request->get('email') && $request->getMethod() === 'POST') {
            $user = $repo->findOneByEmail($request->request->get('email'));

            if ($user instanceof User) {
                $user->setToken(uniqid());

                $tokenExpiration = new \DateTime("+1 week");
                $user->setTokenDisabledAt($tokenExpiration);

                $url = $this->generateUrl('app_new_password', ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
                $email = (new TemplatedEmail())
                    ->to($user->getEmail())
                    ->subject('Récupération de mot de passe')
                    ->htmlTemplate('emails/reset_password.html.twig')
                    ->context([
                        'url' => $url
                    ]);

                try {
                    $this->em->flush();
                    $mailer->send($email);
                    $this->addFlash('success', "Un lien de réinitialisation de mot de passe a été envoyé à l'adresse email indiquée si un compte existe");
                    return $this->redirectToRoute('app_login');
                } catch (Exception $e) {
                    $this->logger->error('Erreur MDP oublié : ' . $e->getMessage());
                    $this->addFlash('danger', 'Une erreur est survenue');
                }
            }

        }

        return $this->render('app/security/password/ask_reset_password.html.twig', [
        ]);

    }

    #[Route('/nouveau-mot-de-passe/{token}', name: 'app_new_password')]
    public function newPassword(?User $user, Request $request): RedirectResponse|Response
    {
        if (!$user || new \DateTime() > $user->getTokenDisabledAt()) {
            $this->addFlash('danger', 'Vous de pouvez pas accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(RepeatedPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $this->hasher->hashPassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);
            $user->setToken(null);
            $user->setTokenDisabledAt(null);

            try {
                $this->em->flush();
            } catch (Exception $e) {
                throw new Exception($e->getMessage(), 500);
            }

            $this->addFlash('success', 'Votre mot de passe a été modifié avec succès!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('app/security/password/form_reset_password.html.twig', [
            'form' => $form->createView()
        ]);

    }
}