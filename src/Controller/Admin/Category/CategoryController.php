<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category\Category;
use App\Entity\Category\MenuCategory;
use App\Enum\MenuCategoryTypeEnum;
use App\Form\Category\CategoryType;
use App\Repository\Category\CategoryRepository;
use App\Services\Menu\MenuService;
use App\Utils\ActionBar;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'admin_category_')]
class CategoryController extends AbstractController
{

    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete(Category $category, EntityManagerInterface $em): RedirectResponse
    {
        $slug = $category->getRoot()->getSlug();
        try {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été supprimée.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenue');
        }

        return $this->redirectToRoute('admin_category_list', ['slug' => $slug]);

    }


    #[Route('/{slug}', name: 'list')]
    public function list(?Category $category, CategoryRepository $repo): Response
    {

        $arrayTree = $repo->childrenHierarchy($category);
        $actions = (new ActionBar())
            ->addAddAction($this->generateUrl('admin_category_add', [
                'slug' => Category::MENU_SLUG
            ]));
        return $this->render('admin/category/list.html.twig', [
            'arrayTree' => $arrayTree,
            'root' => $category,
            'actions' => $actions->getAll(),
            'currentPage' => $this->getCurrentPage($category)

        ]);
    }

    #[Route('/{slug}/ajouter', name: 'add')]
    public function add(Category $root, Request $request, EntityManagerInterface $em, CategoryRepository $repo): RedirectResponse|Response
    {
        if ($root instanceof MenuCategory) {
            $category = new MenuCategory();
            $category->setParent($root);
        } else {
            return $this->redirectToRoute('admin_dashboard');
        }


        $form = $this->createForm(
            CategoryType::class, $category, ['attr' => ['category' => $root]]
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($root instanceof MenuCategory) {
                MenuService::clearFields($category);
            }
            $em->persist($category);
            $em->flush();

            $repo->reorder($category->getParent(), 'sort');
            $repo->recover();
            $em->flush();
            $em->clear();
            $this->addFlash('success', 'La catégorie a bien été ajoutée');
            return $this->redirectToRoute('admin_category_list', ['slug' => $root->getSlug()]);
        }

        $actions = (new ActionBar())
            ->addBackAction($this->generateUrl('admin_category_list', [
                'slug' => $root->getRoot()->getSlug()
            ]))
            ->addSaveAction('category');

        $currentPage = $this->getCurrentPage($category);
        $currentPage['breadcrumb'][] = ['label' => 'Nouvel item',];

        return $this->render('admin/category/edit.html.twig', array(
            'form' => $form->createView(),
            'root' => $root,
            'category' => $category,
            'actions' => $actions->getAll(),
            'currentPage' => $currentPage
        ));
    }

    #[Route('/{root}/{idChild}', name: 'edit')]
    #[ParamConverter('root', options: ['mapping' => ['root' => 'slug']])]
    public function edit(Category $root, int $idChild, Request $request, CategoryRepository $repo, EntityManagerInterface $em): RedirectResponse|Response
    {

        $category = $repo->findOneBy(['root' => $root, 'id' => $idChild]);

        $form = $this->createForm(CategoryType::class, $category, ['attr' => ['category' => $root]]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($category instanceof MenuCategory) {
                if ($category->getChildren()->count() > 0 && $category->getType() === MenuCategoryTypeEnum::ITEM) {
                    $form->get('type')->addError(new FormError('Cet item possède un sous menu. Il ne peux pas être une page.'));
                }
            }

            if ($form->isValid()) {
                if ($root instanceof MenuCategory) {
                    MenuService::clearFields($category);
                }
                $em->persist($category);
                $em->flush();

                $repo->reorder($category->getParent(), 'sort');
                $repo->recover();
                $em->flush();
                $em->clear();
                $this->addFlash('success', 'La catégorie a bien été modifiée');
                return $this->redirectToRoute('admin_category_list', ['slug' => $root->getSlug()]);
            }
        }

        $actions = (new ActionBar())
            ->addBackAction($this->generateUrl('admin_category_list', [
                'slug' => $root->getRoot()->getSlug()
            ]))
            ->addDeleteAction($this->generateUrl('admin_category_delete', [
                'id' => $category->getId()
            ]))
            ->addSaveAction('category');

        $currentPage = $this->getCurrentPage($category);
        $currentPage['breadcrumb'][] = ['label' => $category->getTitle()];

        return $this->render('admin/category/edit.html.twig', array(
            'form' => $form->createView(),
            'root' => $root,
            'category' => $category,
            'actions' => $actions->getAll(),
            'currentPage' => $currentPage
        ));
    }


    private function getCurrentPage(Category $category): array
    {
        if ($category instanceof MenuCategory) {
            return [
                'menu' => [
                    'id' => 'pages_content',
                    'action' => 'menu'
                ],
                'breadcrumb' => [
                    [
                        'label' => 'Menus',
                        'link' => $this->generateUrl('admin_category_list', ['slug' => Category::MENU_SLUG])
                    ]
                ]
            ];
        }
        return [];
    }
}
