<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InGeneratorBadges extends Command
{
    protected static $defaultName = 'nae:localconfig:InGeneratorBadges';

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    public function __construct(PropertiesUtilsV3 $propertiesUtilsV3)
    {
        parent::__construct();
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $statusItemsTemplate = file_get_contents(
            __DIR__ . '/templates/badges/Badge.txt'
        );

        $badgeFile = LocalConfigUtilsV3::getNaeSfsCpStoragePath() . '/badge.json';
        $badgeItems = [];
        if (file_exists($badgeFile)) {
            $badgeItems = json_decode(
                file_get_contents($badgeFile),
                true
            );
        }

        foreach ($badgeItems as $badgeItem) {
            $imports = [];

            $generatedPath = Utils::generatedPath('badges/' . $badgeItem['config']['schema']);

            $compName = Utils::fixComponentName(
                ucfirst($badgeItem['config']['schema']) .
                ucfirst($badgeItem['config']['slug']) . 'Badge'
            );

            $fileName = $generatedPath . '/' . $compName . '.tsx';

            $hookName = EntitiesUtilsV3::elementHook($badgeItem['config']['schema']);

            $badgeContent = '';
            $badgeVariant = '';
            $property = null;

            if (isset($badgeItem['config']['bgColor'])) {
                $badgeVariant = '"' . $badgeItem['config']['bgColor'] . '"';
            }

            if (isset($badgeItem['config']['path']) && $badgeItem['config']['path']) {
                $badgeContent = '<DfValueView path="' . $badgeItem['config']['path'] . '" id={element.id} />';
                $property = $this->propertiesUtilsV3->getPropertyForPath($badgeItem['config']['path']);
                $enums = $this->propertiesUtilsV3->getPropertyEnumsList($property);

                if (count($enums)) {
                    $pathA = explode(".", $badgeItem['config']['path']);
                    $lastPath = $pathA[count($pathA) - 1];

                    $enumCompName = Utils::fixComponentName(ucfirst($property['entity']) . 'Enums');
                    $funcName = 'get' . $enumCompName;
                    $funcNameColors = 'get' . $enumCompName . 'Colors';

                    $badgeContent = $funcName . '("' . $lastPath . '", element["' . $lastPath . '"])';
                    $badgeVariant = $funcNameColors . '("' . $lastPath . '", element["' . $lastPath . '"])';

                    $imports[] = 'import { ' . $funcName . ', ' . $funcNameColors . ' } from "../../enums/view/' . $enumCompName . '";';
                }
            }
            if (isset($badgeItem['config']['text'])) {
                $badgeContent = '"' . $badgeItem['config']['text'] . '"';
            }

            $importsStr = implode("\n", $imports);

            $generatedContent = str_replace(
                [
                    'TP_COMP_NAME',
                    'TP_HOOK_NAME',
                    'TP_SLUG',
                    'TP_VARIANT',
                    'TP_BADGE_CONTENT',
                    'TP_IMPORT',
                ],
                [
                    $compName,
                    $hookName,
                    $badgeItem['config']['slug'],
                    $badgeVariant,
                    $badgeContent,
                    $importsStr,
                ],
                $statusItemsTemplate
            );

            Utils::writeOnChanges($fileName, $generatedContent);
        }

        return Command::SUCCESS;
    }
}