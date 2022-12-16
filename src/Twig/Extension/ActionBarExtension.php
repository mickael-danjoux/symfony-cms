<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ActionBarExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ActionBarExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('actionBar', [ActionBarExtensionRuntime::class, 'getActionBar'], ['needs_environment' => true, 'needs_context' => true, 'is_safe' => ['html']]),
        ];
    }
}
