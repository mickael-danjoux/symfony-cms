<?php

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(): Response
    {
        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/admin/clear-cache", name="admin_clear_cache")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function cacheClear(): RedirectResponse
    {
        $process = new Process(['php', '../bin/console', 'cache:clear']);
        try {
            $process->mustRun();
            $this->addFlash('success','Le cache à été vidé.');
        } catch (ProcessFailedException $exception) {
            $this->addFlash('warning','Le cache n\'a pas pu être vidé.');
        }
        return $this->redirectToRoute('admin_index');
    }


}
