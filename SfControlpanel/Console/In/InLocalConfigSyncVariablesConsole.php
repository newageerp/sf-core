<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InLocalConfigSyncVariablesConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InLocalConfigSyncVariables';

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
        $configPhpPath = LocalConfigUtils::getPhpVariablesPath() . '/NaeSVariables.php';
        $configPath = Utils::customFolderPath('config') . '/NaeSVariables.tsx';
        $fileContent = '';

        $variables = LocalConfigUtils::getCpConfigFileData('variables');

        $phpFileContent = '<?php
namespace App\\Config;

class NaeSVariables {
';

        $dbData = [];
        foreach ($variables as $variable) {
            $dbData[$variable['config']['slug']] = $variable['config']['text'];

            $phpFileContent .= '
    public static function get' . ucfirst($variable['config']['slug']) . '(): string {
        return "' . $variable['config']['text'] . '";
    }
';
        }

        $phpFileContent .= '}
';

        $fileContent .= 'export const NaeSVariables = ' . json_encode($dbData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents(
            $configPath,
            $fileContent
        );

        file_put_contents(
            $configPhpPath,
            $phpFileContent
        );

        return Command::SUCCESS;
    }
}
