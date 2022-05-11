<?php

namespace App\Controller\Admin;

use App\DataTable\Referencing\ReferencingTableType;
use App\Entity\Referencing\Referencing;
use App\Form\Referencing\ReferencingType;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/referencement', name: 'admin_referencing_')]
#[IsGranted("ROLE_SUPER_ADMIN")]
class ReferencingController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface        $logger
    )
    {
    }

    #[Route('', name: 'list')]
    public function index(DataTableFactory $dataTableFactory, Request $request): Response
    {
        $datatable = $dataTableFactory
            ->createFromType(ReferencingTableType::class)
            ->handleRequest($request);

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }

        return $this->render('admin/referencing/list.html.twig', [
            'datatable' => $datatable,
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

        $form = $this->createForm(ReferencingType::class, $referencing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->persist($referencing);
                $this->em->flush();
                $this->addFlash('success', 'La page a bien été éditée.');
                return $this->redirectToRoute('admin_referencing_edit', ['id' => $referencing->getId()]);
            } catch (\Exception $e) {
                $this->logger->critical('Erreur durant l’édition du referencement', [
                    'error' => $e
                ]);
                $this->addFlash('danger', 'Une erreur est survenue');
            }
        }

        return $this->render('admin/referencing/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/supprimer/{id}', name: 'remove')]
    public function remove(Referencing $referencing): RedirectResponse
    {
        try{
            $this->em->remove($referencing);
            $this->em->flush();
            $this->addFlash('success', 'La page a bien été supprimée.');
        }catch (\Exception $e){
            $this->addFlash('danger', 'Une erreur est survenue.');
            $this->logger->critical('Erreur durant la suppression du referencement',[
                'error' => $e
            ]);
        }
        return $this->redirectToRoute('admin_referencing_list');
    }
}
