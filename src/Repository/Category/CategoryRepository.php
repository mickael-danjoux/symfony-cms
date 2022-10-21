<?php

namespace App\Repository\Category;

use App\Entity\Category\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category|null findOneBySlug(string $slug)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends NestedTreeRepository
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(Category::class));
    }

    // PATCH BUG BUNDLE
    public function getNodesHierarchyQueryBuilder($node = null, $direct = false, array $options = [], $includeNode = false): QueryBuilder
    {
        return $this->childrenQueryBuilder(
            $node,
            $direct,
            null,
            'ASC',
            $includeNode
        );
    }
}
