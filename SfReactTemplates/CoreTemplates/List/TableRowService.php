<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Buttons\FindButton;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\CustomColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\FloatColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\NumberColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\StringColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\AddSelectButton;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\UniColumn;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTd;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTr;
use Newageerp\SfReactTemplates\Event\TableTdRenderEvent;
use Newageerp\SfUservice\Service\UService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfReactTemplates\Event\TableColumnSettingsEvent;

class TableRowService
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
        if (mb_strpos($type, 'float') === 0 || $type === 'number' || $type === 'seconds-to-time') {
            return 'text-right';
        }

        return 'text-left';
    }

    public function buildSimpleDataRow(array $columns)
    {
        $tr = new TableTr();
        foreach ($columns as $col) {
            $pathArray = explode(".", $col['path']);

            $type = isset($col['type']) ? $col['type'] : 'string';

            $td = new TableTd();

            $alignment = isset($col['alignment']) ? $col['alignment'] : $this->getColAlignment($type);

            if ($alignment !== 'text-left') {
                $td->setTextAlignment($alignment);
            }

            if ($type === 'number') {
                $tpl = new NumberColumn($pathArray[1]);
            } else if ($type === 'float2') {
                $tpl = new FloatColumn($pathArray[1]);
            } else {
                $tpl = new StringColumn($pathArray[1]);
            }

            if (isset($col['link']) && is_array($col['link'])) {
                $tplTmp = $tpl;
                $tpl = new FindButton(
                    $col['link']['entity'],
                    $col['link']['field'],
                    $col['link']['value'],
                );
                $tpl->getChildren()->addTemplate($tplTmp);
            }

            $td->getContents()->addTemplate($tpl);

            $tr->getContents()->addTemplate($td);
        }
        return $tr;
    }

    public function buildDataRow(string $schema, string $type, ?bool $addSelectButton = false): TableTr
    {
        $forcePopup = $addSelectButton;

        // BUILD TR/TH
        $tr = new TableTr();

        if ($addSelectButton) {
            $td = new TableTd();
            $td->getContents()->addTemplate(new AddSelectButton());
            $tr->getContents()->addTemplate($td);
        }

        $tab = $this->getTabsUtilsV3()->getTabBySchemaAndType($schema, $type);
        if ($tab) {
            foreach ($tab['columns'] as $col) {

                $td = new TableTd();

                $event = new TableColumnSettingsEvent($col, $tab);
                $this->eventDispatcher->dispatch($event, TableColumnSettingsEvent::NAME);
                $col = $event->getCol();

                $pathArray = explode(".", $col['path']);
                $level1Path = $pathArray[0] . '.' . $pathArray[1];

                if (isset($col['componentName']) && $col['componentName']) {
                    $td->getContents()->addTemplate(
                        new CustomColumn(
                            $pathArray[1],
                            $pathArray[0],
                            $col['componentName']
                        )
                    );
                } else {
                    $filterPath = isset($col['filterPath']) && $col['filterPath'] ? str_replace('i.', $schema . '.', $col['filterPath']) : $col['path'];
                    $filterProp = $this->getPropertiesUtilsV3()->getPropertyForPath($filterPath);

                    $prop = $this->getPropertiesUtilsV3()->getPropertyForPath($level1Path);

                    if ($prop) {
                        $styleArray = [
                            'className' => [],
                        ];
                        $alignment = $this->getPropertiesUtilsV3()->getPropertyTableAlignment($filterProp, $col);
                        if ($alignment !== 'text-left') {
                            $styleArray['alignment'] = $alignment;
                        }

                        $naeType = $this->propertiesUtilsV3->getPropertyNaeType($prop, $col);

                        $td = new UniColumn($col['path'], $pathArray[1]);
                        if (isset($col['editable']) && $col['editable']) {
                            $td->setEditable(true);
                        }

                        if ($naeType === 'date') {
                            $styleArray['className'][] = 'whitespace-nowrap';
                        }


                        if ($naeType === 'string_array' || $naeType === 'text') {
                            $td->appendOptions(
                                'multiRow',
                                [
                                    'initRows' => 3,
                                    'initShowAll' => false,
                                    'expandIn' => 'inline'
                                ]
                            );
                        }

                        if (isset($col['link']) && $col['link'] > 0) {
                            $td->setLink([
                                'defaultClick' => $forcePopup ? 'none' : ($col['link'] === 10 ? 'main' : 'modal')
                            ]);
                        }

                        $td->setStyle($styleArray);

                        // if (isset($col['templateOptions'])) {
                        //     foreach ($col['templateOptions'] as $opt) {
                        //         $optMethod = 'set' . ucfirst($opt['prop']);
                        //         if (method_exists($tpl, $optMethod)) {
                        //             $tpl->$optMethod($opt['value']);
                        //         }
                        //     }
                        // }
                        // if ($tpl) {
                        //     if (isset($col['link']) && $col['link'] > 0 && $naeType !== 'object') {
                        //         $rsButton = new RsButton($prop['entity'], 'tData.element.id');
                        //         if ($forcePopup) {
                        //             $rsButton->setDefaultClick('none');
                        //         } else {
                        //             $rsButton->setDefaultClick($col['link'] === 10 ? 'main' : 'modal');
                        //         }
                        //         $rsButton->getChildren()->addTemplate($tpl);

                        //         $td->getContents()->addTemplate($rsButton);
                        //     } else {
                        //         $td->getContents()->addTemplate($tpl);
                        //     }
                        // }
                    }
                }

                // $event = new TableTdRenderEvent($schema, $type, $td, $col);
                // $this->eventDispatcher->dispatch($event, TableTdRenderEvent::NAME);

                $tr->getContents()->addTemplate($td);
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
