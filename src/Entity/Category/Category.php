<?php

namespace App\Entity\Category;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\Category\CategoryRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(fields: ['slug'], message: "L’URL de cette catégorie existe déjà: {{ value }}" )]
#[Gedmo\Tree(type: 'nested')]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorMap([
    'category' => self::class,
    'categoryMenu' => MenuCategory::class,
])]
class Category
{
    CONST MENU_SLUG = 'menu';

    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le titre')]
    protected ?string $title = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Gedmo\TreeLeft]
    protected ?int $lft = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Gedmo\TreeLevel]
    protected ?int $lvl = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Gedmo\TreeRight]
    protected ?int $rgt = null;


    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Gedmo\TreeRoot]
    protected Category $root;


    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[Gedmo\TreeParent]
    protected ?Category $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    #[ORM\OrderBy(['lft' => 'ASC'])]
    protected Collection $children;

    #[ORM\Column(type: Types::STRING, length: 255)]
    protected string $slug;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => 1])]
    #[Assert\NotBlank(message: 'Veuillez un ordre d’affichage')]
    #[Assert\GreaterThanOrEqual(value: 1 ,message: 'La valeur minimal est 1')]
    protected int $sort = 1;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int|null
     */
    public function getLft(): ?int
    {
        return $this->lft;
    }

    /**
     * @param int|null $lft
     */
    public function setLft(?int $lft): void
    {
        $this->lft = $lft;
    }

    /**
     * @return int|null
     */
    public function getLvl(): ?int
    {
        return $this->lvl;
    }

    /**
     * @param int|null $lvl
     */
    public function setLvl(?int $lvl): void
    {
        $this->lvl = $lvl;
    }

    /**
     * @return int|null
     */
    public function getRgt(): ?int
    {
        return $this->rgt;
    }

    /**
     * @param int|null $rgt
     */
    public function setRgt(?int $rgt): void
    {
        $this->rgt = $rgt;
    }

    /**
     * @return Category
     */
    public function getRoot(): Category
    {
        return $this->root;
    }

    /**
     * @param Category $root
     */
    public function setRoot(Category $root): void
    {
        $this->root = $root;
    }

    /**
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }

    /**
     * @param Category|null $parent
     */
    public function setParent(?Category $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getChildren(): ArrayCollection|Collection
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection|Collection $children
     */
    public function setChildren(ArrayCollection|Collection $children): void
    {
        $this->children = $children;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    #[ORM\PreFlush]
    public function generateSlug(): void
    {
        $slugger = new Slugify();
        $this->setSlug($slugger->slugify($this->title));
    }

}

