<?php

namespace App\Controller\Admin;

use App\Utils\ActionBar;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sherlockode\ConfigurationBundle\Form\Type\ParametersType;
use Sherlockode\ConfigurationBundle\Manager\ParameterManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/channel', name: 'admin_channel_')]
class ChannelController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(EntityManagerInterface $em, LoggerInterface $logger, Request $request, ParameterManagerInterface $parameterManager): Response
    {
        $data = $parameterManager->getAll();
        $parametersForm = $this->createForm(ParametersType::class, $data)->handleRequest($request);


        if($parametersForm->isSubmitted() && $parametersForm->isValid()){
            try{
                $params = $parametersForm->getData();
                foreach ($params as $path => $value) {
                    $parameterManager->set($path, $value);
                }
                $parameterManager->save();
                $this->addFlash('success', 'La configuration a été sauvegardée.');
            }catch (\Exception $e){
                $this->addFlash('danger', 'Une erreur est survenue.');
                $logger->critical('Impossible d’enregistrer les paramètres du site ' .$e->getMessage(),[
                    'error' => $e
                ]);
            }
        }

        $actions = (new ActionBar())
            ->addSaveAction('parameters')
        ;
        $currentPage = [
            'menu' => ['id' => 'channel_config'],
            'breadcrumb' => [
                ['label' => 'Configuration']
            ]
        ];

        return $this->render('admin/channel/index.html.twig', [
            'parametersForm' => $parametersForm->createView(),
            'actions' => $actions->getAll(),
            'currentPage' => $currentPage
        ]);
    }
}
