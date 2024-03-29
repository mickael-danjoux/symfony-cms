<?php

namespace App\Controller\Admin;

use App\DataTable\User\UserTableType;
use App\Entity\User\User;
use App\Form\User\AdminUserType;
use App\Utils\ActionBar;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\DataTableFactory;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administrateurs', name: 'admin_user_')]
class AdminUserController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger
    )
    {
    }

    #[Route('', name: 'list')]
    public function index(DataTableFactory $dataTableFactory, Request $request): Response
    {
        $datatable = $dataTableFactory
            ->createFromType(UserTableType::class, ['admin' => true])
            ->handleRequest($request);

        if ($datatable->isCallback()) {
            return $datatable->getResponse();
        }
        $actions = (new ActionBar())
            ->addAddAction($this->generateUrl('admin_user_add'));
        $currentPage = $this->getCurrentPage();

        return $this->render('admin/user/list.html.twig', [
            'datatable' => $datatable,
            'currentPage' => $currentPage,
            'actions' => $actions->getAll()
        ]);
    }

    #[Route('/ajouter', name: 'add')]
    #[Route('/editer/{id}', name: 'edit')]
    public function edit(?User $user, Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $currentPage = $this->getCurrentPage();
        if ($request->attributes->get("_route") === 'admin_user_edit' && !$user instanceof User) {
            $this->addFlash('danger', "Page introuvable");
            return $this->redirectToRoute('admin_user_list');
        } elseif ($request->attributes->get("_route") === 'admin_user_add') {
            $user = new User();
            $currentPage['breadcrumb'][] = ['label' => 'Nouvel Admin'];
        }else{
            $currentPage['breadcrumb'][] = ['label' => $user->getDisplayName()];
        }

        $form = $this->createForm(AdminUserType::class, $user);

        if (in_array('ROLE_ADMIN', $user->getRoles()))
            $form->get('roles')->setData('ROLE_ADMIN');

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles()))
            $form->get('roles')->setData('ROLE_SUPER_ADMIN');

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            // Dans le cas d'un ajout
            if (!$this->em->contains($user)) {
                if ($form->get('plain_password')->isEmpty()) {
                    $form->get('plain_password')->addError(new FormError('Veuillez renseigner le mot de passe'));
                }
                $user->setIsVerified(true);
                $user->setIsBanned(false);
            }
            // Modification du MDP
            if ($form->get('plain_password')->getData()) {
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->get('plain_password')->getData()
                    )
                );
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if ($form->get('plain_password')->getData()) {
                    $user->setPassword(
                        $hasher->hashPassword(
                            $user,
                            $form->get('plain_password')->getData()
                        )
                    );
                }
                $user->setRoles([$form->get('roles')->getData()]);
                (!$this->em->contains($user)) ? $this->em->persist($user) : null;
                $this->em->flush();
                $this->addFlash('success', 'L’utilisateur a bien été édité.');
                return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);

            } catch (\Exception $e) {
                $this->logger->critical('Erreur durant ajout utilisateur', [
                    'error' => $e
                ]);
                $this->addFlash('danger', 'Une erreur est survenue.');
            }
        }
        $actions = (new ActionBar())
            ->addBackAction($this->generateUrl('admin_user_list'))
            ->addSaveAction('admin_user')
        ;

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'currentPage' => $currentPage,
            'actions' => $actions->getAll()
        ]);
    }

    #[Route('/supprimer/{id}', name: 'remove')]
    public function remove(User $user): RedirectResponse
    {
        try {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'L’utilisateur a bien été supprimée.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenue.');
            $this->logger->critical('Erreur durant la suppression d’un utilisateur', [
                'error' => $e
            ]);
        }
        return $this->redirectToRoute('admin_user_list');
    }

    private function getCurrentPage(): array
    {
        return [
            'menu' => [
                'id' => 'user_admin_list',
            ],
            'breadcrumb' => [
                ['label' => 'Administrateurs', 'link' => $this->generateUrl('admin_user_list')]
            ]
        ];
    }
}
