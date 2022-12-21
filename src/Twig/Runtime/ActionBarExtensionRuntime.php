<?php

namespace App\Twig\Runtime;

use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class ActionBarExtensionRuntime implements RuntimeExtensionInterface
{
    public function getActionBar(Environment $environment, array $context): string
    {
        $actions = null;
        if(isset($context['actions'])) $actions = $context['actions'];

        return $environment->render('admin/partials/_buttons.html.twig', ['actions' => $actions]);
    }
}
