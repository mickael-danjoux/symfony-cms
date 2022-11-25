<?php

namespace App\Entity\Category;

use App\Entity\Page\Page;
use App\Enum\MenuCategoryTypeEnum;
use App\Repository\Category\MenuCategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: MenuCategoryRepository::class)]
#[ORM\Table(name: 'category_menu')]
class MenuCategory extends Category
{

    #[Column(type: "string", nullable: false, enumType: MenuCategoryTypeEnum::class)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le type')]
    private MenuCategoryTypeEnum $type;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $newTab = false;

    #[ORM\ManyToOne(inversedBy: 'menuCategories')]
    private ?Page $page = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $itemId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $itemClasses = null;


    public function __construct()
    {
        parent::__construct();
        $this->type = MenuCategoryTypeEnum::ITEM;
    }

    /**
     * @return MenuCategoryTypeEnum
     */
    public function getType(): MenuCategoryTypeEnum
    {
        return $this->type;
    }

    /**
     * @param MenuCategoryTypeEnum $type
     */
    public function setType(MenuCategoryTypeEnum $type): void
    {
        $this->type = $type;
    }


    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return bool
     */
    public function isNewTab(): bool
    {
        return $this->newTab;
    }

    /**
     * @param bool $newTab
     */
    public function setNewTab(bool $newTab): void
    {
        $this->newTab = $newTab;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getItemId(): ?string
    {
        return $this->itemId;
    }

    /**
     * @param string|null $itemId
     */
    public function setItemId(?string $itemId): void
    {
        $this->itemId = $itemId;
    }

    /**
     * @return string|null
     */
    public function getItemClasses(): ?string
    {
        return $this->itemClasses;
    }

    /**
     * @param string|null $itemClasses
     */
    public function setItemClasses(?string $itemClasses): void
    {
        $this->itemClasses = $itemClasses;
    }



}

