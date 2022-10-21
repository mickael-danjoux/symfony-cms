<?php

namespace App\Entity\Page;

use App\Entity\Category\MenuCategory;
use App\Entity\Seo\Seo;
use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\Page\PageRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Enum\PageTypeEnum;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[UniqueEntity(fields: ["path", "routeName"], message: "Une autre page utilise un chemin similaire", errorPath: "slug")]
#[ORM\HasLifecycleCallbacks]
class Page
{
    const PAGE_CONTROLLER_PATH = "App\Controller\App\Router\PageController::display";
    const PAGE_DEFAULT_TITLE_PREFIX = '[Brouillon] Page ' ;
    const PAGE_ROUTE_NAME_PREFIX = 'cms_' ;

    use IdTrait;
    use TimestampableTrait;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = "[]";

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $routeName = null;

    #[ORM\Column]
    private ?bool $published = true;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startPublishingAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endPublishingAt = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $controller = null;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: PageTypeEnum::class)]
    private PageTypeEnum $type;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Valid]
    private ?Seo $seo = null;

    #[ORM\OneToMany(mappedBy: 'page', targetEntity: MenuCategory::class)]
    private Collection $menuCategories;

    public function __construct()
    {
        $this->startPublishingAt = new \DateTime();
        $this->menuCategories = new ArrayCollection();
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): self
    {
        $this->routeName = $routeName;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getStartPublishingAt(): ?\DateTimeInterface
    {
        return $this->startPublishingAt;
    }

    public function setStartPublishingAt(\DateTimeInterface $startPublishingAt): self
    {
        $this->startPublishingAt = $startPublishingAt;

        return $this;
    }

    public function getEndPublishingAt(): ?\DateTimeInterface
    {
        return $this->endPublishingAt;
    }

    public function setEndPublishingAt(?\DateTimeInterface $endPublishingAt): self
    {
        $this->endPublishingAt = $endPublishingAt;

        return $this;
    }

    #[ORM\PreFlush]
    public function generateSlug(): void
    {

        $slugger = new Slugify(['regexp' => '/([^A-Za-z0-9-\/]|-)+/']);
        $this->setPath($slugger->slugify($this->path));
    }

    public function getContentAsArray(){
        return json_decode($this->content, true);
    }

    /**
     * @return string|null
     */
    public function getController(): ?string
    {
        return $this->controller;
    }

    /**
     * @param string|null $controller
     */
    public function setController(?string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return PageTypeEnum
     */
    public function getType(): PageTypeEnum
    {
        return $this->type;
    }

    /**
     * @param PageTypeEnum $type
     */
    public function setType(PageTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getSeo(): ?Seo
    {
        return $this->seo;
    }

    public function setSeo(?Seo $seo): self
    {
        $this->seo = $seo;

        return $this;
    }

    /**
     * @return Collection<int, MenuCategory>
     */
    public function getMenuCategories(): Collection
    {
        return $this->menuCategories;
    }

    public function addMenuCategory(MenuCategory $menuCategory): self
    {
        if (!$this->menuCategories->contains($menuCategory)) {
            $this->menuCategories->add($menuCategory);
            $menuCategory->setPage($this);
        }

        return $this;
    }

    public function removeMenuCategory(MenuCategory $menuCategory): self
    {
        if ($this->menuCategories->removeElement($menuCategory)) {
            // set the owning side to null (unless already changed)
            if ($menuCategory->getPage() === $this) {
                $menuCategory->setPage(null);
            }
        }

        return $this;
    }

}
