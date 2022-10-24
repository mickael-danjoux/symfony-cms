<?php

namespace App\Command\Main;
use App\Entity\Category\Category;
use App\Entity\Category\MenuCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:category-tree:init',
    description: 'Initialiser un arbre de catégories',
)]
class CategoryTreeCommand extends Command
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // ...
            ->addOption('class', 'c' , InputOption::VALUE_OPTIONAL, 'Nom de la classe à initialiser')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $category = null;
        $io = new SymfonyStyle($input, $output);
        $classOption = $input->getOption('class');

        if(! $classOption){
            $classOption = $io->askQuestion(new ChoiceQuestion(
                'Sélectionnez un type de catégorie à initialiser',
                // choices can also be PHP objects that implement __toString() method
                ['MenuCategory'],
                0
            ));

        }


        switch ($classOption) {
            case 'MenuCategory':
                $category = new MenuCategory();
                $category->setTitle(Category::MENU_SLUG);
                break;

        }

        $this->em->persist($category);
        $this->em->flush();


        $io->success('Catégorie initialisée.');

        return Command::SUCCESS;
    }

}
