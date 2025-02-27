<?php

namespace Newageerp\SfControlpanel\Console\In;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InRunAll extends Command
{
    protected static $defaultName = 'nae:localconfig:InRunAll';

    public function __construct()
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = [
            'nae:localconfig:InLocalConfigSyncVariables',
            // 'nae:localconfig:InLocalConfigSyncFields',
            // 'nae:localconfig:InLocalConfigSyncEntities',
            // 'nae:localconfig:InLocalConfigSyncUsersPermissions',
            // 'nae:localconfig:InPathMap',
            // 'nae:localconfig:InGeneratorTabs',
            'nae:localconfig:InGeneratorBadges',
            // 'nae:localconfig:InGeneratorEditForms',
            // 'nae:localconfig:InGeneratorViewForms',
            // 'nae:localconfig:InFillModels',
            'nae:localconfig:InGeneratorRoutes',
            'nae:localconfig:InGeneratorFileWidgets',
            // 'nae:localconfig:InGeneratorLayout',
        ];

        shell_exec('rm -rf /tmp/*');

        foreach ($commands as $commandName) {
            // $output->writeln('Running command ' . $commandName);

            // $greetInput = new ArrayInput([]);
            // $command = $this->getApplication()->find($commandName);
            // $returnCode = $command->run($greetInput, $output);

            // $output->writeln('Finnish command ' . $commandName . ' with status code ' . $returnCode);
            $output->writeln('Running command ' . $commandName);

            $outputData = [];
            exec('bin/console '.$commandName, $outputData);

            foreach ($outputData as $l) {
                $output->writeln($l);
            }

            $output->writeln('Finnish command ' . $commandName);
        }

        return Command::SUCCESS;
    }
}
