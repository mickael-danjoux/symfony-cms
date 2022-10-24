<?php

namespace App\Controller\App;

use App\DTO\Contact;
use App\Controller\App\Router\RouterControllerTrait;
use App\Entity\Page\Page;
use App\Form\ContactType;
use App\Services\MailerService;
use Psr\Log\LoggerInterface;
use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Loader\Configurator\Traits\RouteTrait;

class ContactController extends AbstractController
{
    use RouterControllerTrait;

    public function form(Request $request, MailerInterface $mailer, LoggerInterface $logger, MailerService $mailerService ): Response
    {
        $page = $this->getPageOrNotFound($request);

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $email = (new TemplatedEmail())
                    ->subject('Formulaire de contact')
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context([
                        'contact' => $contact
                    ]);

                $mailerService->replaceContactsWithParam($email, 'channel_email_contact');

                $mailer->send($email);
                $this->addFlash('success', "Le formulaire a bien été envoyé");
                return $this->redirectToRoute('app_contact');

            } catch (\Exception $e) {
                $logger->error('ERREUR CONTACT : ' . $e->getMessage());
                $this->addFlash('warning', 'Une erreur est survenue.');
            }
        }

        return $this->render('app/contact/form.html.twig', [
            'form' => $form->createView(),
            'page' => $page
        ]);
    }
}
