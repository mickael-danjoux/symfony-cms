<?php

namespace App\Controller\Admin;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    #[Route( '', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }


    #[Route('/clear-cache', name: 'admin_clear_cache')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function cacheClear(LoggerInterface $logger): RedirectResponse
    {
        $process = new Process(['php', '../bin/console', 'cache:clear']);
        try {
            $process->mustRun();
            $this->addFlash('success', 'Le cache à été vidé.');

        } catch (\Exception $exception) {
            $this->addFlash('warning', 'Le cache n\'a pas pu être vidé.');
            $logger->error('Cache:clear Error : ' . $exception->getMessage());
        }
        return $this->redirectToRoute('admin_index');

    }

}
