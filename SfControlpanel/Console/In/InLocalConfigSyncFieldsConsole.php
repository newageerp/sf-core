<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InLocalConfigSyncFieldsConsole extends Command
{
    protected static $defaultName = 'nae:localconfig:InLocalConfigSyncFields';

    protected EntityManagerInterface $em;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(
        EntityManagerInterface $em,
        PropertiesUtilsV3        $propertiesUtilsV3,
        EntitiesUtilsV3          $entitiesUtilsV3,
    ) {
        parent::__construct();
        $this->em = $em;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $configPath = Utils::customFolderPath('config') . '/NaeSProperties.tsx';
        // $configPathDbKeys = LocalConfigUtils::getFrontendConfigPath() . '/NaeSDbKeys.tsx';
        $configPathKeys = Utils::customFolderPath('config') . '/NaeSPropertiesKeys.tsx';

        $fileContent = 'import { INaeProperty } from "../../v3/utils";
';
        $fileDbKeysContent = '';


        $conn = $this->em->getConnection();
        $sql = 'select TABLE_NAME, COLUMN_NAME, DATA_TYPE from information_schema.columns
        where table_schema = DATABASE()
        order by table_name,ordinal_position';
        $stmt = $conn->query($sql);
        $dbFields = $stmt->fetchAll();

        $dbFieldsAll = [];
        foreach ($dbFields as $dbField) {
            $dbFieldsAll[] = [
                $dbField['TABLE_NAME'],
                $dbField['COLUMN_NAME'],
                $dbField['DATA_TYPE'],
            ];
        }


        $propsData = LocalConfigUtils::getCpConfigFileData('properties');

        $properties = [];
        $propertiesKeys = [];
        $dbDataKeys = [];

        foreach ($propsData as $prop) {
            if (!isset($dbDataKeys[$prop['config']['entity']])) {
                $dbDataKeys[$prop['config']['entity']] = [];
            }
            $dbDataKeys[$prop['config']['entity']][$prop['config']['key']] = $prop['config']['key'];

            $description = str_replace(
                ['|||', '<b>', '</b>', '<hr/>'],
                ["\n\n", '**', '**', '___'],
                $prop['config']['description']
            );

            if (!isset($propertiesKeys[$prop['config']['entity']])) {
                $propertiesKeys[$prop['config']['entity']] = [];
            }

            $propertiesKeys[$prop['config']['entity']][$prop['config']['key']] = $prop['config']['key'];

            $available = [
                'sort' => $prop['config']['available_sort'],
                'filter' => $prop['config']['available_filter'],
                'group' => $prop['config']['available_group'],
                'total' => $prop['config']['available_total'],
            ];

            $propSet = [
                'schema' => $prop['config']['entity'],
                'key' => $prop['config']['key'],
                'type' => $prop['config']['type'],
                'format' => $prop['config']['typeFormat'],
                'title' => $prop['config']['title'],
                'additionalProperties' => json_decode($prop['config']['additionalProperties'], true),
                'description' => $description,
                'className' => $this->entitiesUtilsV3->getClassNameBySlug($prop['config']['entity']),
                'isDb' => $prop['config']['isDb'] === 1 || $prop['config']['isDb'] === true,
                'dbType' => $prop['config']['dbType']
            ];
            if (isset($prop['config']['customAs']) && $prop['config']['customAs']) {
                $propSet['as'] = $prop['config']['customAs'];
            } else if ($prop['config']['as']) {
                $propSet['as'] = $prop['config']['as'];
            }

            $enumsData = [];

            $enumsList = LocalConfigUtils::getCpConfigFileData('enums');
            $enumsList = array_filter(
                $enumsList,
                function ($item) use ($prop) {
                    return $item['config']['entity'] === $prop['config']['entity'] && $item['config']['property'] === $prop['config']['key'];
                }
            );
            foreach ($enumsList as $enum) {
                $enumsData[] = [
                    'sort' => $enum['config']['sort'],
                    'title' => $enum['config']['title'],
                    'value' => $enum['config']['value'],
                    'color' => $enum['config']['badgeVariant'],
                ];
            }


            if ($enumsData) {
                usort($enumsData, function ($pdfA, $pdfB) {
                    if ($pdfA['sort'] < $pdfB['sort']) {
                        return -1;
                    }
                    if ($pdfA['sort'] > $pdfB['sort']) {
                        return 1;
                    }
                    if ($pdfA['title'] < $pdfB['title']) {
                        return -1;
                    }
                    if ($pdfA['title'] > $pdfB['title']) {
                        return 1;
                    }
                    return 0;
                });

                $propSet['enum'] = array_map(
                    function ($en) use ($prop) {
                        $isInt = $prop['config']['type'] === 'integer' || $prop['config']['type'] === 'int' || $prop['config']['type'] === 'number';
                        return [
                            'label' => $en['title'],
                            'value' => $isInt ? ((int)$en['value']) : $en['value'],
                            'color' => $en['color'],
                        ];
                    },
                    $enumsData
                );
            }
            
            $propSet['naeType'] = $this->propertiesUtilsV3->getOldPropertyNaeType($propSet, []);

            $properties[] = $propSet;
        }



        usort($properties, function ($pdfA, $pdfB) {
            if ($pdfA['schema'] < $pdfB['schema']) {
                return -1;
            }
            if ($pdfA['schema'] > $pdfB['schema']) {
                return 1;
            }
            if ($pdfA['key'] < $pdfB['key']) {
                return -1;
            }
            if ($pdfA['key'] > $pdfB['key']) {
                return 1;
            }
            if ($pdfA['title'] < $pdfB['title']) {
                return -1;
            }
            if ($pdfA['title'] > $pdfB['title']) {
                return 1;
            }
            return 0;
        });

        $fileContent .= 'export const NaeSProperties: INaeProperty[] = ' . json_encode($properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileKeysContent = 'export const NaeSPropertiesKeys = ' . json_encode($propertiesKeys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $fileDbKeysContent .= '
export const NaeSDbKeys = ' . json_encode($dbFieldsAll, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents(
            $configPath,
            $fileContent
        );
        file_put_contents(
            $configPathKeys,
            $fileKeysContent
        );
        //        file_put_contents(
        //            $configJsonPath,
        //            json_encode($properties, JSON_PRETTY_PRINT)
        //        );
        // file_put_contents(
        //     $configPathDbKeys,
        //     $fileDbKeysContent
        // );
        //        file_put_contents(
        //            $configJsonPathDbKeys,
        //            json_encode($dbFieldsAll, JSON_PRETTY_PRINT)
        //        );

        return Command::SUCCESS;
    }
}
