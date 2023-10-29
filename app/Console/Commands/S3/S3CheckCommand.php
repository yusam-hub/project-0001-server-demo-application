<?php

namespace App\Console\Commands\S3;

use App\ApiClients\ClientS3Sdk;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use YusamHub\AppExt\SymfonyExt\Console\Commands\BaseConsoleCommand;

class S3CheckCommand extends BaseConsoleCommand
{
    protected function configure(): void
    {
        $this
            ->setName('s3:check')
            ->setDescription('s3:check:description')
            ->setHelp('s3:check:help')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $clientS3Sdk = new ClientS3Sdk();
        if ($clientS3Sdk->check()) {
            $output->writeln($clientS3Sdk->getLogsAsString());
            $output->writeln("SUCCESS");
            return self::SUCCESS;
        }
        $output->writeln($clientS3Sdk->getLogsAsString());
        $output->writeln("FAILURE");
        return self::FAILURE;
    }
}