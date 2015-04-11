<?php

namespace Radix\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RadixServeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('radix:serve')
            ->setDescription('Example serve app')
            ->addArgument(
                'port',
                InputArgument::OPTIONAL,
                'Portnumber'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument('port');
        if (!$port) {
            $port = 8000;
        }
        $output->writeLn("Starting server: http://127.0.0.1:" . $port . '/');
        
        $cmd = "php -S 0.0.0.0:" . $port . ' -t web/';
        exec($cmd);
        return;
    }
}
