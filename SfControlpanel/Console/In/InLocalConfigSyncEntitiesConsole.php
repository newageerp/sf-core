<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InLocalConfigSyncEntitiesConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InLocalConfigSyncEntities';

    protected EntityManagerInterface $em;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configPath = Utils::customFolderPath('config') . '/NaeSSchema.tsx';

        $fileContent = 'import { INaeSchema } from "../../v3/utils"
';

        $entityData = LocalConfigUtils::getCpConfigFileData('entities');

        $entities = [];
        $entitiesMap = [];
        foreach ($entityData as $entity) {
            $status = [
                'className' => $entity['config']['className'],
                'schema' => $entity['config']['slug'],
                'title' => $entity['config']['titleSingle'],
                'titlePlural' => $entity['config']['titlePlural'],
            ];

            if ($entity['config']['required']) {
                $status['required'] = json_decode(
                    $entity['config']['required'],
                    true
                );
            }
            if ($entity['config']['scopes']) {
                $status['scopes'] = json_decode(
                    $entity['config']['scopes'],
                    true
                );
            }

            $entities[] = $status;

            $entitiesMap[$entity['config']['className']] = [
                'className' => $entity['config']['className'],
                'schema' => $entity['config']['slug']
            ];
        }

        usort($entities, function ($pdfA, $pdfB) {
            if ($pdfA['schema'] < $pdfB['schema']) {
                return -1;
            }
            if ($pdfA['schema'] > $pdfB['schema']) {
                return 1;
            }
            return 0;
        });

        $fileContent .= 'export const NaeSSchema: INaeSchema[] = ' . json_encode($entities, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileContent .= '
        export const NaeSSchemaMap = ' . json_encode($entitiesMap, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents(
            $configPath,
            $fileContent
        );

        return Command::SUCCESS;
    }
}
