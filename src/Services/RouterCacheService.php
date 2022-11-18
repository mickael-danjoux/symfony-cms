<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RouterCacheService
{

    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    )
    {
    }

    public function removeCache(): void
    {

        $directories = [
            $this->parameterBag->get('kernel.project_dir') . '/var/cache/prod',
            $this->parameterBag->get('kernel.project_dir') . '/var/cache/dev'
        ];

        $files = [
            'url_generating_routes.php',
            'url_generating_routes.php.meta',
            'url_matching_routes.php',
            'url_matching_routes.php.meta'
        ];
        foreach ($directories as $directory) {
            if (is_dir($directory)) {
                foreach ($files as $file) {
                    if (file_exists($directory . '/' . $file))
                        unlink($directory . '/' . $file);
                }
            }
        }
    }
}
