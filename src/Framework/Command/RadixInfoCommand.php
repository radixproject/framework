<?php

namespace Radix\Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RadixInfoCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('radix:info')
            ->setDescription('Display information about this Radix instance')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $radix = $this->getApplication()->getRadix();
        
        $apps = $radix->getApps();
        $output->writeLn("Registered apps: " . count($apps));
        foreach ($apps as $app) {
            $output->writeLn(" - [" . $app->getName() . "] " . $app->getTitle() . ": " . $app->getDescription());
        }
        $output->writeLn('');

        $types = $radix->getTypes();
        $output->writeLn("Registered types: " . count($types));
        foreach ($types as $type) {
            $output->writeLn(" - `" . $type->getName() . "`: " . $type->getDescription());
            $fields = $type->getFields();
            foreach ($fields as $field) {
                $output->writeLn("   * `" . $field->getName() . "` " . $field->getType() . ": " . $field->getDescription());
            }
        }
        $output->writeLn('');

        return;
    }
}
