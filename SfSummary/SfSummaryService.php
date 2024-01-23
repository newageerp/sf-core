<?php

namespace Newageerp\SfSummary;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;

class SfSummaryService
{
    protected array $configData = [];

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    public function __construct(PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $this->configData = ConfigService::getConfig('summaryV2');
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
    }

    public function getConfig(string $configId)
    {
        $config = array_values(
            array_filter(
                $this->configData,
                function ($item) use ($configId) {
                    return $item['id'] === $configId;
                }
            )
        );

        if (count($config) === 0) {
            return null;
        }
        $config = $config[0];
        return $config;
    }

    public function getAvailableConfigs(string $configId)
    {
        $config = $this->getConfig($configId);

        $availableConfigs = [];
        if (isset($config['group'])) {
            $availableConfigs = array_values(
                array_filter(
                    $this->configData,
                    function ($item) use ($config) {
                        return isset($item['group']) && $item['group'] === $config['group'];
                    }
                )
            );
        }
        return $availableConfigs;
    }

    public function getOutputConfig(array $config, array &$fieldsToReturn)
    {
        $outputConfig = [
            'title' => $config['title'],
            'cols' => [],
            'values' => [],
            'init' => [
                'rows' => $config['initRows'],
                'columns' => $config['initColumns'],
                'values' => $config['initValues']
            ]
        ];

        foreach ($config['cols'] as $row) {
            if (!in_array($row['key'], $fieldsToReturn)) {
                $fieldsToReturn[] = $row['key'];
            }
            $rowConfig = [
                'title' => '',
                'key' => $row['key']
            ];
            if (isset($row['title'])) {
                $rowConfig['title'] = $row['title'];
            } else if ($row['type'] === 'property') {
                $prop = $this->propertiesUtilsV3->getPropertyForPath($config['schema'] . '.' . $row['key']);
                if ($prop) {
                    $rowConfig['title'] = $prop['title'];
                }
            }
            $outputConfig['cols'][] = $rowConfig;
        }

        foreach ($config['values'] as $val) {
            if (!in_array($val['key'], $fieldsToReturn)) {
                $fieldsToReturn[] = $val['key'];
            }

            $valConfig = [
                'title' => '',
                'type' => $val['type'],
                'field' => $val['field'],
                'key' => $val['key'],
            ];
            if (isset($val['title'])) {
                $valConfig['title'] = $val['title'];
            }
            $outputConfig['values'][] = $valConfig;
        }

        return $outputConfig;
    }

    public function processData(array $data, array $config, array &$output, array &$colValues)
    {
        foreach ($data as $el) {

            $outputEl = [];

            foreach ($config['cols'] as $col) {
                $colKey = $col['key'];

                if (!isset($colValues[$colKey])) {
                    $colValues[$colKey] = [];
                }

                $item = $this->getItemValueByPath($el, $colKey);
                $colValues[$colKey][$item] = 1;

                $outputEl[$colKey] = $item;
            }
            foreach ($config['values'] as $col) {
                $colKey = $col['key'];
                
                $item = $this->getItemValueByPath($el, $colKey);

                $outputEl[$colKey] = $item;
            }

            $output[] = $outputEl;
        }

        foreach ($config['cols'] as $col) {
            $colKey = $col['key'];

            $colValues[$colKey] = array_keys($colValues[$colKey]);

            if (isset($col['sort'])) {
                $this->sortArray($col['sort'], $colValues[$colKey]);
            }
        }
    }

    public function processConfigWithData(string $configId, $dataFunc, array &$fieldsToReturn)
    {
        $config = $this->getConfig($configId);
        if (!$config) {
            return ['success' => 0];
        }

        $availableConfigs = $this->getAvailableConfigs($configId);
        $outputConfig = $this->getOutputConfig($config, $fieldsToReturn);

        $data = $dataFunc($outputConfig, $fieldsToReturn);

        $colValues = [];

        $output = [];

        $this->processData($data, $config, $output, $colValues);

        return [
            'success' => 1,
            'config' => $outputConfig,
            'data' => $output,
            'colValues' => $colValues,
            'availableSelections' => array_map(
                function ($item) {
                    return [
                        'id' => $item['id'],
                        'title' => $item['title']
                    ];
                },
                $availableConfigs
            )
        ];
    }

    public function getItemValueByPath(array $el, string $path)
    {
        $item = $el;

        $pathArray = explode(".", $path);
        foreach ($pathArray as $pathItem) {
            if (isset($item[$pathItem])) {
                $item = $item[$pathItem];
            } else {
                $item = '-';
            }
        }
        return $item;
    }

    protected function sortArray(string $sort, array &$array)
    {
        if (mb_strtolower($sort) === 'asc') {
            usort($array, function ($itemA, $itemB) {
                return $itemA <=> $itemB;
            });
        } else {
            usort($array, function ($itemA, $itemB) {
                return $itemB <=> $itemA;
            });
        }
    }
}
