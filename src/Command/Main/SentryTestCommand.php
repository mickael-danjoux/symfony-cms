<?php

namespace App\Command\Main;

use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SentryTestCommand extends Command
{
    protected static $defaultName = 'app:sentry:test';
    private LoggerInterface $logger;

    function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setDescription('Test Sentry Log')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->logger->critical("Test Sentry log",[
                'context' => 'test du context'
            ]);
            $io->success("Log send to sentry");
            return Command::SUCCESS;


        } catch (Exception $ex) {
            $io->error($ex->getMessage());
            return Command::FAILURE;

        }
    }
}
