<?php

namespace App\Routing;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class CmsRouteLoader extends Loader
{
    private bool $isLoaded = false;

    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }


    public function load(mixed $resource, string $type = null): RouteCollection
    {
        if (true === $this->isLoaded) {
            throw new \RuntimeException('Do not add the "CMS" loader twice');
        }

        $routes = new RouteCollection();

        $nodes = $this->em->getRepository(Page::class)->findAllForRouting();
        foreach ($nodes as $node) {
            $defaults = [
                '_controller' => $node['controller'],
                '_pageId' => $node['id']
            ];
            $route = new Route($node['path'], $defaults);
            $routes->add($node['route_name'], $route);
        }

        $this->isLoaded = true;
        return $routes;
    }

    public function supports(mixed $resource, string $type = null): bool
    {
        return 'cms' === $type;
    }
}
