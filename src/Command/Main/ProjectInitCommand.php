<?php

namespace App\Command\Main;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:project:init',
    description: 'Initialisation du projet',
)]
class ProjectInitCommand extends Command
{

    protected function configure(): void
    {
        $this
            ->addOption('no-user', null , InputOption::VALUE_NONE, 'Ne pas créer d’utilisateur')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Initialisation BDD
        try{
            $this->getApplication()->find('app:db:init')->run(new ArrayInput([]), $output);
        }catch (\Exception $e){
            $io->error('Impossible de poursuivre l’initialisation');
            return Command::FAILURE;
        }


        // Création premier utilisateur
        if(! $input->getOption('no-user')){
            try{
                $this->getApplication()->find('app:user:create')->run(new ArrayInput([]), $output);
            }catch (\Exception $e){
                $io->error('impossible de créer l’utilisateur');
            }
        }


        try{
            $this->getApplication()->find('cache:clear')->run(new ArrayInput([]), $output);
        }catch (\Exception $e){
            $io->error('impossible de vider le cache');
        }

        $io->success('Fin de l’initialisation.');


        return Command::SUCCESS;
    }
}
