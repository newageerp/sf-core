<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Newageerp\SfControlpanel\Service\TemplateService;

class InGeneratorEditForms extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorEditForms';

    public function __construct() {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $efCustomComponentsGeneratedPath = Utils::customFolderPath('edit/components');
        $customEfFunctionTemplate = new TemplateService('v3/edit/CustomFieldFunction.html.twig');
        $customEfFunctionTemplateMap = new TemplateService('v3/edit/CustomEditComponentsMap.html.twig');

        $editsFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/edit.json';
        $editItems = [];
        if (file_exists($editsFile)) {
            $editItems = json_decode(
                file_get_contents($editsFile),
                true
            );
        }

        $customComponents = [];

        foreach ($editItems as $editItem) {
            foreach ($editItem['config']['fields'] as $fieldIndex => $field) {
                if (isset($field['componentName']) && $field['componentName']) {
                    $componentNameA = explode("/", $field['componentName']);
                    $customComponentName = end($componentNameA);
                    $componentNamePath = $efCustomComponentsGeneratedPath . '/' . $field['componentName'] . '.tsx';
                    if (!file_exists($componentNamePath)) {
                        Utils::customFolderPath('edit/components/' . implode("/", array_slice($componentNameA, 0, -1)));

                        $customEfFunctionTemplate->writeIfNotExists(
                            $componentNamePath,
                            ['compName' => $customComponentName]
                        );
                    }

                    $customComponents[] = [
                        'componentName' => $field['componentName'],
                        'name' => $customComponentName.mb_substr(md5($field['componentName']), 0, 5),
                    ];
                }
            }
        }

        $customEfFunctionTemplateMap->writeToFileOnChanges(
            Utils::customFolderPath('edit').'/CustomEditComponentsMap.ts',
            ['templates' => array_values($customComponents),]
        );

        return Command::SUCCESS;
    }
}
