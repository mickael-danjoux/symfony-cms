<?php

namespace App\Controller\Admin\Category;

use App\Entity\Category\Category;
use App\Entity\Category\MenuCategory;
use App\Enum\MenuCategoryTypeEnum;
use App\Form\Category\CategoryType;
use App\Repository\Category\CategoryRepository;
use App\Services\Menu\MenuService;
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



        return $this->render('admin/category/list.html.twig', [
            'arrayTree' => $arrayTree,
            'root' => $category
        ]);
    }

    #[Route('/{slug}/ajouter', name: 'add')]
    public function add(Category $parent, Request $request, EntityManagerInterface $em, CategoryRepository $repo): RedirectResponse|Response
    {
        if ($parent instanceof MenuCategory) {
            $category = new MenuCategory();
            $parent = $repo->findOneBySlug(Category::MENU_SLUG);
            $category->setParent($parent);
        } else {
            return $this->redirectToRoute('admin_dashboard');
        }


        $form = $this->createForm(
            CategoryType::class, $category, ['attr' => ['category' => $parent]]
        )->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($parent instanceof MenuCategory) {
                MenuService::clearFields($category);
            }
            $em->persist($category);
            $em->flush();

            $repo->reorder($category->getParent(), 'sort');
            $repo->recover();
            $em->flush();
            $em->clear();
            $this->addFlash('success', 'la catégorie a bien été ajoutée');
            return $this->redirectToRoute('admin_category_list', ['slug' => $parent->getSlug()]);
        }

        return $this->render('admin/category/edit.html.twig', array(
            'form' => $form->createView(),
            'catParent' => $parent,
            'category' => $category,
            'action' => 'ajouter',
        ));
    }

    #[Route('/{parent}/{slugChild}', name: 'edit')]
    #[ParamConverter('parent', options: ['mapping' => ['parent' => 'slug']])]
    public function edit(Category $parent, string $slugChild, Request $request, CategoryRepository $repo, EntityManagerInterface $em): RedirectResponse|Response
    {

        //Can't get the children directly with the param converter
        //because child depends on the slug and id of the parent (not just the parent's slug)
        $category = $repo->findOneBy(['root' => $parent, 'slug' => $slugChild]);

        $form = $this->createForm(CategoryType::class, $category, ['attr' => ['category' => $parent]]);

        $form->handleRequest($request);

        if($form->isSubmitted() ){

            if($category instanceof MenuCategory) {
                if ($category->getChildren()->count() > 0 && $category->getType() === MenuCategoryTypeEnum::ITEM) {
                    $form->get('type')->addError(new FormError('Cet item possède un sous menu. Il ne peux pas être une page.'));
                }
            }

            if ($form->isValid()) {
                if ($parent instanceof MenuCategory) {
                    MenuService::clearFields($category);
                }
                $em->persist($category);
                $em->flush();

                $repo->reorder($category->getParent(), 'sort');
                $repo->recover();
                $em->flush();
                $em->clear();
                $this->addFlash('success', 'la catégorie a bien été modifiée');
                return $this->redirectToRoute('admin_category_list', ['slug' => $parent->getSlug()]);
            }
        }


        return $this->render('admin/category/edit.html.twig', array(
            'form' => $form->createView(),
            'catParent' => $parent,
            'category' => $category,
            'action' => 'modifier'
        ));
    }
}
