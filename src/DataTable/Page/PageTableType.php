<?php

namespace App\DataTable\Page;

use App\Entity\Page\Page;
use App\Enum\PageTypeEnum;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class PageTableType implements DataTableTypeInterface
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly Security $security
    )
    {
    }

    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('title', TextColumn::class, ['label' => "Nom"])
            ->add('actions', TwigColumn::class, [
                'className' => 'text-center',
                'label' => 'Actions',
                'template' => 'admin/_tables/_actions.html.twig',
                'data' => function (Page $page) {
                    $actions['page'] = $page;
                    $actions['edit'] = $this->router->generate('admin_page_edit', ['id' => $page->getId()]);
                    if ($this->security->isGranted('ROLE_SUPER_ADMIN') or $page->getType() != PageTypeEnum::INTERNAL_PAGE)
                        $actions['remove'] = $this->router->generate('admin_page_remove', ['id' => $page->getId()]);

                    return $actions;
                }
            ])
            ->addOrderBy('title', 'ASC');
        $dataTable->createAdapter(ORMAdapter::class, [
            'entity' => Page::class,
            'query' => function (QueryBuilder $builder) {
                return $builder->select('p')
                    ->from(Page::class, 'p');
            }
        ]);

    }
}
