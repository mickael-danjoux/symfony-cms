<?php

namespace App\Controller\App;

use App\Controller\App\Router\AbstractRouterController;
use App\DTO\Contact;
use App\Form\ContactType;
use App\Services\MailerService;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

class ContactController extends AbstractRouterController
{

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
