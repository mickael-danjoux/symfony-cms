<?php

namespace App\Repository\Category;

use App\Entity\Category\MenuCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @extends ServiceEntityRepository<MenuCategory>
 *
 * @method MenuCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MenuCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MenuCategory[]    findAll()
 * @method MenuCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuCategoryRepository extends NestedTreeRepository
{
    public function __construct(EntityManagerInterface $manager)
    {
        parent::__construct($manager, $manager->getClassMetadata(MenuCategory::class));
    }

    public function save(MenuCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MenuCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getMenu(): array
    {
        try {
            $query = $this->createQueryBuilder('node')
                ->addSelect('page')
                ->leftJoin('node.page', 'page')
                ->orderBy('node.root, node.lft', 'ASC')
                ->getQuery()
            ;

            $options = ['decorate' => false];
            $tree = $this->buildTree($query->getArrayResult(), $options);

            if(!empty($tree) && isset(reset($tree)['__children'])) {
                return reset($tree)['__children'];
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return [];
    }
}
