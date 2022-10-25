<?php

namespace App\DataTable\User;

use App\Entity\User\User;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableState;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;

class UserTableType implements DataTableTypeInterface
{
    public function __construct(
        private readonly RouterInterface $router,
    )
    {
    }

    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('displayName', TextColumn::class, ['label' => "Nom"])
            ->add('email', TextColumn::class, ['label' => "Email"])
            ->add('registeredAt', DateTimeColumn::class, [
                'label' => "Créé le",
                'format' => 'd/m/Y'
            ])
            ->add('actions', TwigColumn::class, [
                'className' => 'text-center',
                'label' => 'Actions',
                'template' => 'admin/_tables/_actions.html.twig',
                'data' => function (User $user) {
                    $actions['edit'] = $this->router->generate('admin_user_edit', ['id' => $user->getId()]);
                    $actions['remove'] = $this->router->generate('admin_user_remove', ['id' => $user->getId()]);

                    return $actions;
                }
            ]);

        $dataTable->createAdapter(ORMAdapter::class, [
            'entity' => User::class,
            'query' => function (QueryBuilder $builder) use ($options) {
                $builder->select('u')
                    ->from(User::class, 'u')
                ;
                if(in_array('admin',$options) ){
                    if($options['admin'] === true){
                        $builder->andWhere("JSON_EXTRACT(u.roles, '$') LIKE :roleAdmin")->setParameter('roleAdmin',"%ROLE_ADMIN%");
                        $builder->orWhere("JSON_EXTRACT(u.roles, '$') LIKE :roleSuperAdmin")->setParameter('roleSuperAdmin' , "%ROLE_SUPER_ADMIN%");

                    }
                }
            },
            "criteria" => function (QueryBuilder $builder, DataTableState $state) {
                $search = $state->getGlobalSearch();
                if ($search != "") {
                    $builder->andWhere(
                        "
                           u.displayName LIKE :search
                           OR u.email LIKE :search
                           OR DATE_FORMAT(u.registeredAt, '%d/%m/%Y') LIKE :search
                        "
                    ) ->setParameter('search', '%' . $search . '%');
                }
            },
        ]);
    }
}
