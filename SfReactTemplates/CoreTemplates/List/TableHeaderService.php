<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfReactTemplates\CoreTemplates\Data\DataString;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTh;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTr;
use Newageerp\SfReactTemplates\Event\TableHeaderFilterEnumEvent;
use Newageerp\SfReactTemplates\Event\TableHeaderFilterQueryEvent;
use Newageerp\SfUservice\Service\UService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TableHeaderService
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected SfTabsService $tabsUtilsV3;

    protected UService $uservice;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        PropertiesUtilsV3 $propertiesUtilsV3,
        SfTabsService $tabsUtilsV3,
        UService $uservice,
        EntitiesUtilsV3 $entitiesUtilsV3,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->tabsUtilsV3 = $tabsUtilsV3;
        $this->uservice = $uservice;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getColAlignment(string $type): string
    {
        if ($type === 'float' || $type === 'float4' || $type === 'number' || $type === 'seconds-to-time') {
            return 'text-right';
        }

        return 'text-left';
    }

    public function buildSimpleHeaderRow(array $columns)
    {
        $tr = new TableTr();

        foreach ($columns as $col) {
            $type = isset($col['type']) ? $col['type'] : 'string';

            $str = new DataString($col['title']);
            $th = new TableTh();

            $alignment = isset($col['alignment']) ? $col['alignment'] : $this->getColAlignment($type);

            if ($alignment !== 'text-left') {
                $th->setTextAlignment($alignment);
            }

            $th->getContents()->addTemplate($str);

            $tr->getContents()->addTemplate($th);
        }
        return $tr;
    }

    public function buildHeaderRowForColumns(array $columns, bool $addSelectButton)
    {
        $tr = new TableTr();

        if ($addSelectButton) {
            $td = new TableTh();
            $tr->getContents()->addTemplate($td);
        }


        foreach ($columns as $col) {

            $str = new DataString($col['title']);
            $th = new TableTh();

            $prop = $this->getPropertiesUtilsV3()->getPropertyForPath($col['_filterPropPath']);

            if ($prop) {
                $alignment = $this->getPropertiesUtilsV3()->getPropertyTableAlignment($prop, $col);
                if ($alignment !== 'text-left') {
                    $th->setTextAlignment($alignment);
                }

                if ($prop['isDb'] && $col['title']) {

                    $th->setFilter([
                        'id' => PropertiesUtilsV3::swapSchemaToI($col['_filterPropPath']),
                        'title' => $col['title'],
                        'type' => $this->getPropertiesUtilsV3()->getDefaultPropertySearchComparison($prop, $col),
                        'options' => $col['filterEnums']
                    ]);
                }
            }

            if (isset($col['_sortPath']) && $col['_sortPath']) {
                $th->setSort([
                    [
                        'key' => $col['_sortPath']['asc'],
                        'value' => 'ASC'
                    ],
                    [
                        'key' => $col['_sortPath']['desc'],
                        'value' => 'DESC'
                    ]
                ]);
            }

            $th->getContents()->addTemplate($str);

            $tr->getContents()->addTemplate($th);
        }
        return $tr;
    }

    public function buildHeaderRow(string $schema, string $type, ?bool $addSelectButton = false): TableTr
    {
        // BUILD TR/TH
        $columns = [];


        $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType($schema, $type);
        if ($tab) {
            $columns = array_map(function (array $col) use ($schema, $type) {
                // TITLE
                $title = '';
                if (isset($col['customTitle']) && $col['customTitle']) {
                    $title = $col['customTitle'];
                } else {
                    $titlePath = isset($col['titlePath']) && $col['titlePath'] ? $col['titlePath'] : $col['path'];
                    $propTitle = $this->getPropertiesUtilsV3()->getPropertyForPath($titlePath);
                    if ($propTitle) {
                        $title = $propTitle['title'];
                    }
                }
                $col['title'] = $title;

                // FILTER
                $filterPath = isset($col['filterPath']) && $col['filterPath'] ? str_replace('i.', $schema . '.', $col['filterPath']) : $col['path'];
                $filterProp = $this->getPropertiesUtilsV3()->getPropertyForPath($filterPath);

                $col['_filterPropPath'] = $filterPath;
                if ($filterProp['isDb'] && $col['title']) {
                }
                // FILTER ENUMS
                $enums = $this->getPropertiesUtilsV3()->getDefaultPropertySearchOptions($filterProp, $col);
                $filterPropNaeType = $this->getPropertiesUtilsV3()->getPropertyNaeType($filterProp, $col);
                if ($filterPropNaeType === 'object') {
                    $selectSchema = $filterProp['typeFormat'];

                    $filters = [];
                    $event = new TableHeaderFilterQueryEvent($filters, $filterProp, $schema, $type);
                    $this->eventDispatcher->dispatch($event, TableHeaderFilterQueryEvent::NAME);

                    $data = $this->getUservice()->getListDataForSchema(
                        $selectSchema,
                        1,
                        200,
                        ['id', '_viewTitle'],
                        $event->getFilters(),
                        [],
                        $this->getEntitiesUtilsV3()->getDefaultSortForSchema($selectSchema),
                        [],
                        false
                    );
                    $enums = array_map(
                        function ($item) {
                            return [
                                'value' => $item['id'],
                                'label' => isset($item['_viewTitle']) ? $item['_viewTitle'] : $item['id'],
                            ];
                        },
                        $data['data'],
                    );
                }
                $event = new TableHeaderFilterEnumEvent($enums, $filterProp, $schema, $type);
                $this->eventDispatcher->dispatch($event, TableHeaderFilterEnumEvent::NAME);
                $enums = $event->getEnums();
                $col['filterEnums'] = $enums;

                // SORT
                $sortPath = isset($col['sortPath']) && $col['sortPath'] ? str_replace('i.', $schema . '.', $col['sortPath']) : $col['path'];
                $sortProp = $this->getPropertiesUtilsV3()->getPropertyForPath($sortPath);

                if ($sortProp && $sortProp['isDb']) {
                    $col['_sortPath'] = [
                        'asc' => str_replace($schema . '.', 'i.', $sortPath),
                        'desc' => str_replace($schema . '.', 'i.', $sortPath)
                    ];
                }

                return $col;
            }, $tab['columns']);
        }
        return $this->buildHeaderRowForColumns($columns, $addSelectButton);
    }

    /**
     * Get the value of propertiesUtilsV3
     *
     * @return PropertiesUtilsV3
     */
    public function getPropertiesUtilsV3(): PropertiesUtilsV3
    {
        return $this->propertiesUtilsV3;
    }

    /**
     * Set the value of propertiesUtilsV3
     *
     * @param PropertiesUtilsV3 $propertiesUtilsV3
     *
     * @return self
     */
    public function setPropertiesUtilsV3(PropertiesUtilsV3 $propertiesUtilsV3): self
    {
        $this->propertiesUtilsV3 = $propertiesUtilsV3;

        return $this;
    }

    /**
     * Get the value of tabsUtilsV3
     *
     * @return SfTabsService
     */
    public function getTabsUtilsV3(): SfTabsService
    {
        return $this->tabsUtilsV3;
    }

    /**
     * Set the value of tabsUtilsV3
     *
     * @param SfTabsService $tabsUtilsV3
     *
     * @return self
     */
    public function setTabsUtilsV3(SfTabsService $tabsUtilsV3): self
    {
        $this->tabsUtilsV3 = $tabsUtilsV3;

        return $this;
    }

    /**
     * Get the value of uservice
     */
    public function getUservice()
    {
        return $this->uservice;
    }

    /**
     * Set the value of uservice
     */
    public function setUservice($uservice): self
    {
        $this->uservice = $uservice;

        return $this;
    }

    /**
     * Get the value of entitiesUtilsV3
     *
     * @return EntitiesUtilsV3
     */
    public function getEntitiesUtilsV3(): EntitiesUtilsV3
    {
        return $this->entitiesUtilsV3;
    }

    /**
     * Set the value of entitiesUtilsV3
     *
     * @param EntitiesUtilsV3 $entitiesUtilsV3
     *
     * @return self
     */
    public function setEntitiesUtilsV3(EntitiesUtilsV3 $entitiesUtilsV3): self
    {
        $this->entitiesUtilsV3 = $entitiesUtilsV3;

        return $this;
    }
}
