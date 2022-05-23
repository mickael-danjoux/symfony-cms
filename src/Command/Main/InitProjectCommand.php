<?php

namespace App\Command\Main;

use App\Services\Referencing\ReferencingFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:project:init',
    description: 'Initialization du projet',
)]
class InitProjectCommand extends Command
{


    public function __construct(
        private ReferencingFactory $referencingFactory,
        private LoggerInterface $logger
    )
    {
        parent::__construct();

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Création premier utilisateur
        try{
            $io->title('Création d’un utilisateur');
            $this->getApplication()->find('app:user:create')->run($input, $output);
        }catch (\Exception $e){
            $io->error('impossible de créer l’utilisateur');
        }


        // Référencement page d’accueil
        try{
            $io->title('Initialisation du référencement');
            $this->referencingFactory->createFirstPage();
            $io->success('');

        }catch (\Exception $e){
            $message = 'Impossible de créer la première page de référencement';
            $io->error($message);
            $this->logger->critical($message,[
                'error' => $e
            ]);
        }
        return Command::SUCCESS;
    }
}
