<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\TabsUtilsV3;
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

    protected TabsUtilsV3 $tabsUtilsV3;

    protected UService $uservice;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        PropertiesUtilsV3 $propertiesUtilsV3,
        TabsUtilsV3 $tabsUtilsV3,
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

    public function buildHeaderRow(string $schema, string $type, ?bool $addSelectButton = false): TableTr
    {
        // BUILD TR/TH
        $tr = new TableTr();

        if ($addSelectButton) {
            $td = new TableTh();
            $tr->getContents()->addTemplate($td);
        }

        $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType($schema, $type);
        if ($tab) {
            foreach ($tab['columns'] as $col) {
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
                $str = new DataString($title);
                $th = new TableTh();

                $filterPath = isset($col['filterPath']) && $col['filterPath'] ? str_replace('i.', $schema . '.', $col['filterPath']) : $col['path'];
                $prop = $this->getPropertiesUtilsV3()->getPropertyForPath($filterPath);

                if ($prop) {
                    $alignment = $this->getPropertiesUtilsV3()->getPropertyTableAlignment($prop, $col);
                    if ($alignment !== 'tw3-text-left') {
                        $th->setTextAlignment($alignment);
                    }

                    if ($prop['isDb'] && $title) {
                        $enums = $this->getPropertiesUtilsV3()->getDefaultPropertySearchOptions($prop, $col);

                        $propNaeType = $this->getPropertiesUtilsV3()->getPropertyNaeType($prop, $col);
                        if ($propNaeType === 'object') {
                            $selectSchema = $prop['typeFormat'];

                            $filters = [];
                            $event = new TableHeaderFilterQueryEvent($filters, $prop, $schema, $type);
                            $this->eventDispatcher->dispatch($event, TableHeaderFilterQueryEvent::NAME);

                            $data = $this->getUservice()->getListDataForSchema(
                                $selectSchema,
                                1,
                                100,
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

                        $event = new TableHeaderFilterEnumEvent($enums, $prop, $schema, $type);
                        $this->eventDispatcher->dispatch($event, TableHeaderFilterEnumEvent::NAME);

                        $enums = $event->getEnums();

                        $th->setFilter([
                            'id' => PropertiesUtilsV3::swapSchemaToI($filterPath),
                            'title' => $title,
                            'type' => $this->getPropertiesUtilsV3()->getDefaultPropertySearchComparison($prop, $col),
                            'options' => $enums
                        ]);
                    }
                }

                $th->getContents()->addTemplate($str);

                $tr->getContents()->addTemplate($th);
            }
        }
        return $tr;
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
     * @return TabsUtilsV3
     */
    public function getTabsUtilsV3(): TabsUtilsV3
    {
        return $this->tabsUtilsV3;
    }

    /**
     * Set the value of tabsUtilsV3
     *
     * @param TabsUtilsV3 $tabsUtilsV3
     *
     * @return self
     */
    public function setTabsUtilsV3(TabsUtilsV3 $tabsUtilsV3): self
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
