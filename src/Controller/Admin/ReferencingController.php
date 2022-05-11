<?php

namespace App\Controller\Admin;

use App\Entity\Referencing\Referencing;
use App\Form\Referencing\ReferencingType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/referencement', name: 'admin_referencing_')]
class ReferencingController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private LoggerInterface $logger)
    {
    }

    #[Route('', name: 'list')]
    public function index(): Response
    {
        return $this->render('admin/referencing/list.html.twig', [
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    #[Route('/editer/{id}', name: 'edit')]
    public function edit(?Referencing $referencing, Request $request): Response
    {
        if ($request->attributes->get("_route") === 'admin_referencing_edit' && !$referencing instanceof Referencing) {
            $this->addFlash('danger', "Page introuvable");
            return $this->redirectToRoute('admin_referencing_list');
        } elseif ($request->attributes->get("_route") === 'admin_referencing_add') {
            $referencing = new Referencing();
        }

        $form =$this->createForm(ReferencingType::class, $referencing);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            try{
                $this->em->persist($referencing);
                $this->em->flush();
                $this->addFlash('success', 'La page a bien été éditée.');
                return $this->redirectToRoute('admin_referencing_edit',['id' => $referencing->getId()]);
            }catch (\Exception $e){
                $this->logger->critical('Erreur durant l’édition du referencement',[
                    'error' => $e
                ]);
                $this->addFlash('danger', 'Une erreur est survenue');
            }
        }

        return $this->render('admin/referencing/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
