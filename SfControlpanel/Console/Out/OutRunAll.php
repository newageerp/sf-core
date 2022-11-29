<?php

namespace Newageerp\SfControlpanel\Console\Out;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

class OutRunAll extends Command
{
    protected static $defaultName = 'nae:localconfig:OutRunAll';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = [
            'cache:clear',
//            'nae:localconfig:OutCacheDocJson',
            'nae:localconfig:OutLocalConfigBuildMock',
            'nae:localconfig:OutLocalConfigSyncSchemas',
            'nae:localconfig:OutLocalConfigSyncProperties',
        ];

        foreach ($commands as $commandName) {
            $output->writeln('Running command ' . $commandName);

            shell_exec('bin/console '.$commandName);

            $output->writeln('Finnish command ' . $commandName);

            
        }

        return Command::SUCCESS;
    }
}
