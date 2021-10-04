<?php

namespace App\Controller\Front;

use App\Classes\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function index(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('success' , 'Le formulaire à bien été envoyé');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render('front/contact/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
