<?php

namespace Radix\App\Example\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleTestCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('example:test')
            ->setDescription('Example app test command')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Your name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeLn("Hello " . $name);
        return;
    }
}
