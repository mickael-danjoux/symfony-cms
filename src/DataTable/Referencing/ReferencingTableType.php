<?php

namespace App\DataTable\Referencing;

use App\Entity\Referencing\Referencing;
use App\Repository\Referencing\ReferencingRepository;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;

class ReferencingTableType implements DataTableTypeInterface
{
    public function __construct(
        private RouterInterface       $router,
        private ReferencingRepository $referencingRepository
    )
    {
    }

    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('pageName', TextColumn::class, ['label' => "Page"])
            ->add('url', TextColumn::class, ['label' => "Url"])
            ->add('actions', TwigColumn::class, [
                'className' => 'text-center',
                'label' => 'Actions',
                'template' => 'admin/_tables/_actions.html.twig',
                'data' => function (Referencing $referencing) {
                    $actions['edit'] = $this->router->generate('admin_referencing_edit', ['id' => $referencing->getId()]);
                    $actions['remove'] = $this->router->generate('admin_referencing_remove',['id' => $referencing->getId()]);

                    return $actions;
                }
            ])
            ->addOrderBy('pageName');

        $dataTable->createAdapter(ORMAdapter::class, [
            'entity' => Referencing::class,
        ]);

    }
}