<?php

namespace App\Controller\App;

use App\Classes\Contact;
use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request, MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $email = (new TemplatedEmail())
                    ->subject('Formulaire de contact')
                    ->htmlTemplate('emails/contact.html.twig')
                    ->context([
                        'contact' => $contact
                    ])
                ;

                $mailer->send($email);
                $this->addFlash('success',"Le formulaire a bien été envoyé");
                return $this->redirectToRoute('app_contact');

            }catch (\Exception $e){
                $logger->error('ERREUR CONTACT : ' . $e->getMessage());
                $this->addFlash('warning','Une erreur est survenue.');
            }
        }

        return $this->render('app/contact/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
