<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InLocalConfigSyncStatusesConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InLocalConfigSyncStatuses';

    protected EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configPath = LocalConfigUtils::getFrontendConfigPath() . '/NaeSStatuses.tsx';

        $fileContent = 'import { INaeStatus } from "../_generated/v3/utils";";
';

        $statusData = LocalConfigUtils::getCpConfigFileData('statuses');

        $statuses = [];
        foreach ($statusData as $status) {
            $statuses[] = [
                'type' => $status['config']['type'],
                'status' => (int)$status['config']['status'],
                'text' => $status['config']['text'],
                'bgColor' => $status['config']['color'],
                'brightness' => (int)str_replace('b', '', $status['config']['brightness']),
                'schema' => $status['config']['entity'],
                'variant' => isset($status['config']['badgeVariant']) ? $status['config']['badgeVariant'] : 'blue',
            ];
        }

        usort($statuses, function ($pdfA, $pdfB) {
            if ($pdfA['schema'] < $pdfB['schema']) {
                return -1;
            }
            if ($pdfA['schema'] > $pdfB['schema']) {
                return 1;
            }
            if ($pdfA['status'] < $pdfB['status']) {
                return -1;
            }
            if ($pdfA['status'] > $pdfB['status']) {
                return 1;
            }
            return 0;
        });

        $fileContent .= 'export const NaeSStatuses: INaeStatus[] = ' . json_encode($statuses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents(
            $configPath,
            $fileContent
        );
        //        file_put_contents(
        //            $configJsonPath,
        //            json_encode($statuses, JSON_PRETTY_PRINT)
        //        );

        return Command::SUCCESS;
    }
}
