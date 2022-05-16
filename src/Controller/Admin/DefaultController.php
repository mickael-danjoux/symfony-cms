<?php

namespace App\Controller\Admin;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(  name: 'admin_')]
class DefaultController extends AbstractController
{

    #[Route( '', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/clear-cache', name: 'clear_cache')]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function cacheClear(LoggerInterface $logger,KernelInterface $kernel): RedirectResponse
    {
        try {
            $application = new Application($kernel);
            $application->setAutoExit(false);
            $application->setAutoExit(false);
            $input = new ArrayInput([
                'command' => 'cache:clear',
            ]);
            $output = new BufferedOutput();
            $application->run($input, $output);
            $this->addFlash('success', 'Le cache a bien été vidé.');

        } catch (\Exception $exception) {
            $this->addFlash('warning', 'Le cache n\'a pas pu être vidé.');
            $logger->error('Cache:clear Error : ' . $exception->getMessage());
        }
        return $this->redirectToRoute('admin_index');
    }
}
