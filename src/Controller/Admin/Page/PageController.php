<?php

namespace App\Controller\Admin\Page;

use App\DataTable\Page\PageTableType;
use App\Entity\Page\Page;
use App\Enum\PageTypeEnum;
use App\Form\Page\PageType;
use App\Services\RouterCacheService;
use App\Utils\ActionBar;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Omines\DataTablesBundle\DataTableFactory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Factories\Page\PageFactory;

#[Route('/pages', name: 'admin_page_')]
class PageController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger
    )
    {
    }


    #[Route('', name: 'list')]
    public function list(DataTableFactory $dataTableFactory, Request $request): Response
    {
        $datatable = $dataTableFactory
            ->createFromType(PageTableType::class)
            ->handleRequest($request);

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }
        $actions = (new ActionBar())
            ->addAddAction($this->generateUrl('admin_page_add'));

        return $this->render('admin/page/list.html.twig', [
            'datatable' => $datatable,
            'actions' => $actions->getAll(),
            'currentPage' => $this->getCurrentPage()
        ]);
    }


    #[Route('/ajouter', name: 'add')]
    #[Route('/editer/{id}', name: 'edit')]
    public function edit(?Page $page, Request $request, PageFactory $pageFactory,  RouterCacheService $routerCacheService): Response
    {

        if ($request->attributes->get("_route") === 'admin_page_edit' && !$page instanceof Page) {
            $this->addFlash('danger', "Page introuvable");
            return $this->redirectToRoute('admin_page_list');
        } elseif ($request->attributes->get("_route") === 'admin_page_add') {
            try{
                $page = $pageFactory->createForForm();
				$routerCacheService->removeCache();
                return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);
            }catch (\Exception $e){
                $this->logger->critical('Impossible de créer une page : ' . $e->getMessage());
            }
        }

        $form = $this->createForm(PageType::class, $page)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($page->getType() === PageTypeEnum::CUSTOM_PAGE && $page->getController() === null) {
                    $page->setController(Page::PAGE_CONTROLLER_PATH);
                }

                $this->em->persist($page);
                $this->em->flush();

                $this->addFlash('success', 'La page a bien été éditée.');
                $routerCacheService->removeCache();

                return $this->redirectToRoute('admin_page_edit', ['id' => $page->getId()]);

            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue');
                $this->logger->critical('Erreur durant l’edition d’une page',[
                    'error' => $e
                ]);
            }
        }

        $actions = (new ActionBar())
            ->addBackAction($this->generateUrl('admin_page_list'))
            ->addDeleteAction($this->generateUrl('admin_page_remove',[
                'id' => $page->getId()
            ]))
            ->addSaveAction('page')
            ->addPreviewAction('#')
        ;

        $currentPage =  $this->getCurrentPage();
        $currentPage['breadcrumb'][] = ['label' => $page->getTitle()];

        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
            'actions' => $actions->getAll(),
            'currentPage' => $currentPage
        ]);
    }

    #[Route('/supprimer/{id}', name: 'remove')]
    public function remove(Page $page): RedirectResponse
    {
        try{
            $this->em->remove($page);
            $this->em->flush();
            $this->addFlash('success', 'La page a bien été supprimée.');
        }catch (\Exception $e){
            $this->addFlash('danger', 'Une erreur est survenue.');
            $this->logger->critical('Erreur durant la suppression d’une page',[
                'error' => $e
            ]);
        }
        return $this->redirectToRoute('admin_page_list');
    }

    private function getCurrentPage(): array
    {
        return [
            'menu' => [
                'id' => 'pages_content',
                'action' => 'pages'
            ],
            'breadcrumb' => [
                [
                    'label' => 'Pages',
                    'link' => $this->generateUrl('admin_page_list')
                ]
            ]
        ];
    }
}
