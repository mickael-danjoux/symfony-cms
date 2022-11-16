<?php

namespace App\Command\Main;

use App\Factories\Menu\DefaultMenuFactory;
use App\Factories\Page\DefaultPagesFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:db:init',
    description: 'Initialise la base de donnée',
)]
class DatabaseCommand extends Command
{


    public function __construct(
        private readonly DefaultPagesFactory $defaultPagesFactory,
        private readonly DefaultMenuFactory $defaultMenuFactory
    )
    {
        parent::__construct();

    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Initialisation de la base de donnée.');

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('La base va être purgée. Continuer ? [N/y]', false);

        if (!$helper->ask($input, $output, $question)) {
            $io->warning('La base de données n’a pas été initialisée.');

            return Command::SUCCESS;
        } else {
            $this->getApplication()->find('doctrine:schema:drop')->run(new ArrayInput(['--full-database' => true, '--force' => true]), $output);
            $input = new ArrayInput([]);
            $input->setInteractive(false);
            $this->getApplication()->find('doctrine:migrations:migrate')->run($input, $output);

            // Initialisation des catégories
            try {
                $this->getApplication()->find('app:category-tree:init')->run(new ArrayInput([
                    '--class' => 'MenuCategory'
                ]), new BufferedOutput());
            } catch (\Exception $e) {
                $io->error('impossible d’initialiser les catégories');
            }

            // Création des pages de bases
            try {
                $this->defaultPagesFactory->createAll();
                $io->info('Pages de bases créées');
            } catch (\Exception $e) {
                $io->error('impossible d’initialiser les pages de base.');
            }

            // Création du menu
            try {
                $this->defaultMenuFactory->createAll();
                $io->info('Menu créé');
            } catch (\Exception $e) {
                dump($e->getMessage());
                $io->error('impossible d’initialiser le menu');
            }

            $io->success('Base de donnée initialisée.');

        }

        return Command::SUCCESS;
    }

}
