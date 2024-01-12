<?php

namespace Newageerp\SfSummary;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfUservice\Service\UService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/app/nae-core/summary")
 */
class SfSummaryController extends OaBaseController
{
    /**
     * @Route(path="/stat")
     */
    public function summary(Request $request, UService $uService, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $request = $this->transformJsonBody($request);

        if (!($user = $this->findUser($request))) {
            throw new \Exception('Bad user');
        }
        AuthService::getInstance()->setUser($user);

        $summaryConfigs = ConfigService::getConfig('summary');

        $cacheToken = $request->get('cacheToken', "");
        $configId = $request->get('configId', '');

        $config = array_values(
            array_filter(
                $summaryConfigs,
                function ($item) use ($configId) {
                    return $item['id'] === $configId;
                }
            )
        );

        if (count($config) === 0) {
            return $this->json(['success' => 0]);
        }
        $config = $config[0];

        $availableConfigs = [];
        if (isset($config['group'])) {
            $availableConfigs = array_values(
                array_filter(
                    $summaryConfigs,
                    function ($item) use ($config) {
                        return isset($item['group']) && $item['group'] === $config['group'];
                    }
                )
            );
        }

        $cacheResult = json_decode(base64_decode($cacheToken), true);
        $fieldsToReturn = $cacheResult['fieldsToReturn'];

        $outputConfig = [
            'title' => $config['title'],
            'rows' => [],
            'columns' => [],
            'values' => [],
        ];

        foreach ($config['rows'] as $row) {
            if (!in_array($row['key'], $fieldsToReturn)) {
                $fieldsToReturn[] = $row['key'];
            }
            $rowConfig = [
                'title' => '',
            ];
            if (isset($row['title'])) {
                $rowConfig['title'] = $row['title'];
            } else if ($row['type'] === 'property') {
                $prop = $propertiesUtilsV3->getPropertyForPath($config['schema'] . '.' . $row['key']);
                if ($prop) {
                    $rowConfig['title'] = $prop['title'];
                }
            }
            $outputConfig['rows'][] = $rowConfig;
        }
        foreach ($config['columns'] as $col) {
            if (!in_array($col['key'], $fieldsToReturn)) {
                $fieldsToReturn[] = $col['key'];
            }
            $colConfig = [
                'title' => '',
            ];
            if (isset($col['title'])) {
                $colConfig['title'] = $col['title'];
            } else if ($col['type'] === 'property') {
                $prop = $propertiesUtilsV3->getPropertyForPath($config['schema'] . '.' . $col['key']);
                if ($prop) {
                    $colConfig['title'] = $prop['title'];
                }
            }
            $outputConfig['columns'][] = $colConfig;
        }
        foreach ($config['values'] as $val) {
            if (!in_array($val['key'], $fieldsToReturn)) {
                $fieldsToReturn[] = $val['key'];
            }

            $valConfig = [
                'title' => '',
                'type' => $val['type'],
                'field' => $val['field']
            ];
            if (isset($val['title'])) {
                $valConfig['title'] = $val['title'];
            }
            $outputConfig['values'][] = $valConfig;
        }

        $uListData = $uService->getListDataFromCacheToken($cacheToken, [
            'fieldsToReturn' => $fieldsToReturn
        ]);

        $columnValues = [];
        $rowValues = [];
        $valKeys = [];
        $valInit = [];

        $output = [];

        foreach ($config['rows'] as $rowKey => $row) {
            $rowValues[$rowKey] = [];
            foreach ($uListData['data'] as $el) {
                $item = $this->getItemValueByPath($el, $row['key']);
                $rowValues[$rowKey][$item] = 1;
            }
            $rowValues[$rowKey] = array_keys($rowValues[$rowKey]);

            if (isset($row['sort'])) {
                $this->sortArray($row['sort'], $rowValues[$rowKey]);
            }
        }
        foreach ($config['columns'] as $columnKey => $col) {
            $columnValues[$columnKey] = [];
            foreach ($uListData['data'] as $el) {
                $item = $this->getItemValueByPath($el, $col['key']);
                $columnValues[$columnKey][$item] = 1;
            }
            $columnValues[$columnKey] = array_keys($columnValues[$columnKey]);

            if (isset($col['sort'])) {
                $this->sortArray($col['sort'], $columnValues[$columnKey]);
            }
        }

        foreach ($config['values'] as $val) {
            $valKeys[] = $val['field'];
            $valInit[$val['field']] = 0;
        }

        foreach ($uListData['data'] as $el) {
            $outputKey = [];

            foreach ($config['rows'] as $rowKey => $row) {
                $res = $this->getItemValueByPath($el, $row['key']);
                $key = array_search($res, $rowValues[$rowKey]);

                $outputKey[] = 'r' . $rowKey . ':' . $key . ':r' . $rowKey;
            }
            foreach ($config['columns'] as $columnKey => $col) {
                $res = $this->getItemValueByPath($el, $col['key']);
                $key = array_search($res, $columnValues[$columnKey]);

                $outputKey[] = 'c' . $columnKey . ':' . $key . ':c' . $columnKey;
            }

            $outputKey = implode("|", $outputKey);
            if (!isset($output[$outputKey])) {
                $output[$outputKey] = $valInit;
            }
            foreach ($config['values'] as $val) {
                $valKey = $val['field'];

                if ($val['type'] === 'count') {
                    $output[$outputKey][$valKey]++;
                } else if ($val['type'] === 'sum') {
                    $res = $this->getItemValueByPath($el, $val['key']);
                    $output[$outputKey][$valKey] += $res;
                }
            }
        }

        return $this->json([
            'success' => 1,
            'config' => $outputConfig,
            'data' => $output,
            'rowValues' => $rowValues,
            'columnValues' => $columnValues,
            'availableSelections' => array_map(
                function ($item) {
                    return [
                        'id' => $item['id'],
                        'title' => $item['title']
                    ];
                },
                $availableConfigs
            )
        ]);
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

    protected function getItemValueByPath(array $el, string $path)
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

    /**
     * @Route(path="/statV2")
     */
    public function summaryV2(Request $request, UService $uService, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $request = $this->transformJsonBody($request);

        if (!($user = $this->findUser($request))) {
            throw new \Exception('Bad user');
        }
        AuthService::getInstance()->setUser($user);

        $summaryConfigs = ConfigService::getConfig('summaryV2');

        $cacheToken = $request->get('cacheToken', "");
        $configId = $request->get('configId', '');

        $config = array_values(
            array_filter(
                $summaryConfigs,
                function ($item) use ($configId) {
                    return $item['id'] === $configId;
                }
            )
        );

        if (count($config) === 0) {
            return $this->json(['success' => 0]);
        }
        $config = $config[0];

        $availableConfigs = [];
        if (isset($config['group'])) {
            $availableConfigs = array_values(
                array_filter(
                    $summaryConfigs,
                    function ($item) use ($config) {
                        return isset($item['group']) && $item['group'] === $config['group'];
                    }
                )
            );
        }

        $cacheResult = json_decode(base64_decode($cacheToken), true);
        $fieldsToReturn = $cacheResult['fieldsToReturn'];

        $outputConfig = [
            'title' => $config['title'],
            'cols' => [],
            'values' => [],
        ];

        foreach ($config['cols'] as $row) {
            if (!in_array($row['key'], $fieldsToReturn)) {
                $fieldsToReturn[] = $row['key'];
            }
            $rowConfig = [
                'title' => '',
            ];
            if (isset($row['title'])) {
                $rowConfig['title'] = $row['title'];
            } else if ($row['type'] === 'property') {
                $prop = $propertiesUtilsV3->getPropertyForPath($config['schema'] . '.' . $row['key']);
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
                'field' => $val['field']
            ];
            if (isset($val['title'])) {
                $valConfig['title'] = $val['title'];
            }
            $outputConfig['values'][] = $valConfig;
        }

        $uListData = $uService->getListDataFromCacheToken($cacheToken, [
            'fieldsToReturn' => $fieldsToReturn
        ]);

        $colValues = [];

        $output = [];
        foreach ($uListData['data'] as $el) {

            $outputEl = [];

            foreach ($config['cols'] as $col) {
                $colKey = $col['key'];

                if (!isset($colValues[$colKey])) {
                    $colValues[$colKey] = [];
                }

                $item = $this->getItemValueByPath($el, $row['key']);
                $colValues[$colKey][$item] = 1;

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

        return $this->json([
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
        ]);
    }
}
