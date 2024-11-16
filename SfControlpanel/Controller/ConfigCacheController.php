<?php

namespace Newageerp\SfControlpanel\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\EditFormsUtilsV3;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\ViewFormsUtilsV3;
use Newageerp\SfDefaults\Service\SfDefaultsService;
use Newageerp\SfEntity\SfEntityService;
use Newageerp\SfProperties\Service\FieldsToReturnService;
use Newageerp\SfTabs\Service\SfTabsService;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfStatus\Service\StatusService;

/**
 * @Route(path="/app/nae-core/config-cache")
 */
class ConfigCacheController extends ConfigBaseController
{
    /**
     * @Route(path="/getLocalConfig", methods={"GET"})
     * @OA\Post (operationId="NaeConfigLocalConfigList")
     */
    public function getLocalConfig(
        Request $request,
    ) {
        $request = $this->transformJsonBody($request);

        $output = ['data' => []];

        try {

            $output['data']['settings'] = ConfigService::getConfig('settings');
            $output['data']['main'] = ConfigService::getConfig('main');
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }

    /**
     * @Route(path="/getCache", methods={"GET"})
     */
    public function getConfig(
        Request $request,
        EntitiesUtilsV3 $entitiesUtilsV3,
        FieldsToReturnService $fieldsToReturnService,
        ViewFormsUtilsV3 $viewFormsUtilsV3,
        EditFormsUtilsV3 $editFormsUtilsV3,
        PropertiesUtilsV3 $propertiesUtilsV3,
        SfTabsService $tabsUtilsV3,
        StatusService $statusService,
        EntityManagerInterface $em,
    ) {
        $request = $this->transformJsonBody($request);

        // entities
        $entites = $entitiesUtilsV3->getEntities();
        $entites = array_map(function ($item) use ($em) {
            $className = SfEntityService::entityByName($item['config']['className']);
            return [
                'slug' => $item['config']['slug'],
                'db' => class_exists($className) && SfEntityService::isEntity($em, $className) ? $em->getClassMetadata($className)->getTableName() : '',
                'title' => [
                    'single' => $item['config']['titleSingle'],
                    'plural' => $item['config']['titlePlural']
                ]
            ];
        }, $entites);

        // edit
        $editForms = $editFormsUtilsV3->getEditForms();
        $editForms = array_map(function (array $item) use ($fieldsToReturnService) {
            return [
                'entity' => $item['config']['schema'],
                'type' => $item['config']['type'],
                'fields' => $fieldsToReturnService->fieldsToReturnForForm($item['config'])
            ];
        }, $editForms);

        // view
        $viewForms = $viewFormsUtilsV3->getViewForms();
        $viewForms = array_map(function (array $item) use ($fieldsToReturnService) {
            return [
                'entity' => $item['config']['schema'],
                'type' => $item['config']['type'],
                'fields' => $fieldsToReturnService->fieldsToReturnForForm($item['config'])
            ];
        }, $viewForms);

        // tabs
        $tabs = $tabsUtilsV3->getTabs();
        $tabs = array_map(function (array $item) use ($fieldsToReturnService, $tabsUtilsV3) {
            return [
                'entity' => $item['config']['schema'],
                'type' => $item['config']['type'],
                'fields' => $fieldsToReturnService->fieldsToReturnForTab($item['config']),
                'sort' => $tabsUtilsV3->getTabSort($item['config']['schema'], $item['config']['type'])
            ];
        }, $tabs);

        // properties
        $enumsList = LocalConfigUtils::getCpConfigFileData('enums');

        $properties = $propertiesUtilsV3->getProperties();
        $properties = array_map(function ($item) use ($enumsList, $propertiesUtilsV3) {
            $enums = array_map(
                function ($item) {
                    return [
                        'sort' => $item['config']['sort'],
                        'title' => $item['config']['title'],
                        'value' => $item['config']['value'],
                        'color' => isset($item['config']['badgeVariant']) && $item['config']['badgeVariant'] ? $item['config']['badgeVariant'] : 'slate',
                    ];
                },
                array_filter(
                    $enumsList,
                    function ($enum) use ($item) {
                        return $enum['config']['entity'] === $item['config']['entity'] && $enum['config']['property'] === $item['config']['key'];
                    }
                )
            );
            if (count($enums) > 0) {
                usort($enums, function ($pdfA, $pdfB) {
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

                $enums = array_map(
                    function ($en) use ($item) {
                        $isInt = $item['config']['type'] === 'integer' || $item['config']['type'] === 'int' || $item['config']['type'] === 'number';
                        return [
                            'label' => $en['title'],
                            'value' => $isInt ? ((int)$en['value']) : $en['value'],
                            'color' => $en['color'],
                        ];
                    },
                    $enums
                );
            }

            $propAs = null;
            if (isset($item['config']['customAs']) && $item['config']['customAs']) {
                $propAs = $item['config']['customAs'];
            } else if ($item['config']['as']) {
                $propAs = $item['config']['as'];
            }
            $itemForType = $item['config'];
            $itemForType['format'] = $itemForType['typeFormat'];
            $itemForType['enum'] = $enums;
            $type = $propAs ? $propAs : $propertiesUtilsV3->getOldPropertyNaeType($itemForType, []);

            return [
                'entity' => $item['config']['entity'],
                'key' => $item['config']['key'],
                'dbKey' => isset($item['config']['dbKey']) ? $item['config']['dbKey'] : '',
                'rel' => $item['config']['typeFormat'],
                'enums' => $enums,
                'type' => $type
            ];
        }, $properties);

        return $this->json([
            'data' => [
                'entities' => $entites,
                'properties' => $properties,
                'editForms' => $editForms,
                'viewForms' => $viewForms,
                'statuses' => $statusService->getStatusesV2(),

                'settings' => ConfigService::getConfig('settings'),

                'config' => [
                    'main' => ConfigService::getConfig('main'),
                ]
            ]
        ]);
    }
}
