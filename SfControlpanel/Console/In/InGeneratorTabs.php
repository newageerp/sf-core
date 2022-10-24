<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Service\TemplateService;

class InGeneratorTabs extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorTabs';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/tmp/smarty',
        ]);

        $customColumnFunctionTemplate = $twig->load('tabs/CustomColumnFunction.html.twig');

        $customEfFunctionTemplateMap = new TemplateService('v3/list/CustomListComponentsMap.html.twig');

        $tabItems = LocalConfigUtils::getCpConfigFileData('tabs');

        $tabCustomComponentsGeneratedPath = Utils::customFolderPath('tabs/components');

        $customComponents = [];

        foreach ($tabItems as $tabItem) {
            foreach ($tabItem['config']['columns'] as $column) {
                if (isset($column['componentName']) && $column['componentName']) {
                    if (mb_strpos($column['componentName'], 'pdf:') === 0) {
                        
                    } else {

                        $componentNameA = explode("/", $column['componentName']);
                        $customComponentName = end($componentNameA);
                        $componentNamePath = $tabCustomComponentsGeneratedPath . '/' . $column['componentName'] . '.tsx';
                        if (!file_exists($componentNamePath)) {
                            Utils::customFolderPath('tabs/components/' . implode("/", array_slice($componentNameA, 0, -1)));

                            $generatedContent = $customColumnFunctionTemplate->render(['compName' => $customComponentName]);
                            Utils::writeOnChanges($componentNamePath, $generatedContent);
                        }

                        $customComponents[$column['componentName']] = [
                            'componentName' => $column['componentName'],
                            'name' => $customComponentName.mb_substr(md5($column['componentName']), 0, 5),
                        ];
                    }

                    
                }
            }
            
        }

        $customEfFunctionTemplateMap->writeToFileOnChanges(
            Utils::customFolderPath('tabs').'/CustomListComponentsMap.ts',
            ['templates' => array_values($customComponents),]
        );

        return Command::SUCCESS;
    }
}
