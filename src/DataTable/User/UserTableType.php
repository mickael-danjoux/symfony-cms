<?php

namespace App\DataTable\User;

use App\Entity\User\User;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;

class UserTableType implements DataTableTypeInterface
{
    public function __construct(
        private RouterInterface       $router,
    )
    {
    }
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('displayName', TextColumn::class, ['label' => "Nom"])
            ->add('email', TextColumn::class, ['label' => "Email"])
            ->add('actions', TwigColumn::class, [
                'className' => 'text-center',
                'label' => 'Actions',
                'template' => 'admin/_tables/_actions.html.twig',
                'data' => function (User $user) {
                    $actions['edit'] = $this->router->generate('admin_user_edit', ['id' => $user->getId()]);
                    $actions['remove'] = $this->router->generate('admin_user_remove',['id' => $user->getId()]);

                    return $actions;
                }
            ])
        ;


        $dataTable->createAdapter(ORMAdapter::class, [
            'entity' => User::class,
        ]);
    }
}