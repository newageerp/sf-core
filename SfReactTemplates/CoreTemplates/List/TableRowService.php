<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Buttons\FindButton;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfReactTemplates\CoreTemplates\Buttons\RsButton;
use Newageerp\SfReactTemplates\CoreTemplates\Buttons\RsButtonTemplate;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\AudioColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\BoolColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\ColorColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\CustomColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\DateColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\DateTimeColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\EnumMultiNumberColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\EnumMultiTextColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\EnumNumberColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\EnumTextColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\FileColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\FileMultipleColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\FloatColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\ImageColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\LargeTextColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\NumberColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\ObjectColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\StatusColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\StringArrayColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\StringColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\Columns\AddSelectButton;
use Newageerp\SfReactTemplates\CoreTemplates\List\EditableColumns\BoolEditableColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\EditableColumns\FloatEditableColumn;
use Newageerp\SfReactTemplates\CoreTemplates\List\EditableColumns\LargeTextEditableColumn;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTd;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTh;
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
                            $col['componentName']
                        )
                    );
                } else {
                    $filterPath = isset($col['filterPath']) && $col['filterPath'] ? str_replace('i.', $schema . '.', $col['filterPath']) : $col['path'];
                    $filterProp = $this->getPropertiesUtilsV3()->getPropertyForPath($filterPath);

                    $prop = $this->getPropertiesUtilsV3()->getPropertyForPath($level1Path);

                    if ($prop) {
                        $alignment = $this->getPropertiesUtilsV3()->getPropertyTableAlignment($filterProp, $col);
                        if ($alignment !== 'text-left') {
                            $td->setTextAlignment($alignment);
                        }

                        $naeType = $this->propertiesUtilsV3->getPropertyNaeType($prop, $col);

                        $tpl = null;
                        // if ($naeType === 'array') {
                        //     [$tabSchema, $tabType] = explode(':', $field['arrayRelTab']);

                        //     $wideRow->getControlContent()->addTemplate(
                        //         new ArrayRoField(
                        //             $pathArray[1],
                        //             $tabSchema,
                        //             $tabType,
                        //         )
                        //     );
                        // }
                        if ($naeType === 'audio') {
                            $tpl = new AudioColumn($pathArray[1]);
                        }
                        if ($naeType === 'bool') {
                            if (isset($col['editable']) && $col['editable']) {
                                $tpl = new BoolEditableColumn($pathArray[1]);
                                $tpl->setSchema($schema);
                            } else {
                                $tpl = new BoolColumn($pathArray[1]);
                            }
                        }
                        if ($naeType === 'color') {
                            $tpl = new ColorColumn($pathArray[1]);
                        }
                        if ($naeType === 'date') {
                            $tpl = new DateColumn($pathArray[1]);
                            $td->setClassName('whitespace-nowrap');
                        }
                        if ($naeType === 'datetime') {
                            $tpl = new DateTimeColumn($pathArray[1]);
                        }
                        if ($naeType === 'enum_multi_number') {
                            $tpl = new EnumMultiNumberColumn(
                                $pathArray[1],
                                $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                            );
                        }
                        if ($naeType === 'enum_multi_text') {
                            $tpl = new EnumMultiTextColumn(
                                $pathArray[1],
                                $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                            );
                        }
                        if ($naeType === 'enum_number') {
                            $tpl = new EnumNumberColumn(
                                $pathArray[1],
                                $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                            );
                        }
                        if ($naeType === 'enum_text') {
                            $tpl = new EnumTextColumn(
                                $pathArray[1],
                                $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                            );
                        }
                        if ($naeType === 'file') {
                            $tpl = new FileColumn($pathArray[1]);
                        }
                        if ($naeType === 'fileMultiple') {
                            $tpl = new FileMultipleColumn($pathArray[1]);
                        }
                        if ($naeType === 'float') {

                            if (isset($col['editable']) && $col['editable']) {
                                $tpl = new FloatEditableColumn($pathArray[1]);
                                $tpl->setAccuracy(2);
                                $tpl->setSchema($schema);
                            } else {
                                $tpl = new FloatColumn($pathArray[1]);
                            }
                        }
                        if ($naeType === 'float4') {
                            if (isset($col['editable']) && $col['editable']) {
                                $tpl = new FloatEditableColumn($pathArray[1]);
                                $tpl->setAccuracy(4);
                                $tpl->setSchema($schema);
                            } else {
                                $tpl = new FloatColumn($pathArray[1], 4);
                            }
                        }
                        if ($naeType === 'image') {
                            $tpl = new ImageColumn($pathArray[1]);
                        }
                        if ($naeType === 'text') {
                            if (isset($col['editable']) && $col['editable']) {
                                $tpl = new LargeTextEditableColumn($pathArray[1], isset($prop['as']) ? $prop['as'] : '');
                                $tpl->setSchema($schema);
                            } else {
                                $tpl = new LargeTextColumn($pathArray[1], isset($prop['as']) ? $prop['as'] : '');
                            }
                        }
                        if ($naeType === 'number') {
                            $tpl = new NumberColumn($pathArray[1]);
                        }
                        if ($naeType === 'object') {
                            $objectProp = $this->propertiesUtilsV3->getPropertyForPath($col['path']);
                            $objectNaeType = $this->propertiesUtilsV3->getPropertyNaeType($objectProp, $col);

                            $fieldPath = $pathArray;
                            unset($fieldPath[0]);

                            $idPath = array_values($fieldPath);
                            $idPath[count($idPath) - 1] = 'id';

                            $fieldkey = implode(".", $fieldPath);
                            $idKey = implode(".", $idPath);

                            $objectField = new ObjectColumn(
                                $fieldkey,
                                $idKey,
                                $objectProp['entity']
                            );
                            $objectField->setFieldType($objectNaeType);
                            $objectField->setAs($prop['as']);

                            if (isset($col['link']) && $col['link'] > 0) {
                                if ($forcePopup) {
                                    $objectField->setHasLink('none');
                                } else {
                                    $objectField->setHasLink($col['link'] === 10 ? 'main' : 'modal');
                                }
                            } else {
                                $objectField->setHasLink(null);
                            }



                            $tpl = $objectField;
                        }
                        if ($naeType === 'status') {
                            $tpl = new StatusColumn(
                                $pathArray[1],
                                $prop['entity']
                            );
                        }
                        if ($naeType === 'status-short') {
                            $tpl = new StatusColumn(
                                $pathArray[1],
                                $prop['entity']
                            );
                            $tpl->setIsSmall(true);
                        }
                        if ($naeType === 'string_array') {
                            $tpl = new StringArrayColumn($pathArray[1]);
                        }
                        if ($naeType === 'string') {
                            if (isset($col['editable']) && $col['editable']) {
                                $tpl = new LargeTextEditableColumn($pathArray[1], isset($prop['as']) ? $prop['as'] : '');
                                $tpl->setSchema($schema);
                            } else {
                                $tpl = new StringColumn($pathArray[1]);
                            }
                        }

                        if (isset($col['templateOptions'])) {
                            foreach ($col['templateOptions'] as $opt) {
                                $optMethod = 'set' . ucfirst($opt['prop']);
                                if (method_exists($tpl, $optMethod)) {
                                    $tpl->$optMethod($opt['value']);
                                }
                            }
                        }

                        if ($tpl) {
                            if (isset($col['link']) && $col['link'] > 0 && $naeType !== 'object') {
                                $rsButton = new RsButton($prop['entity'], 'tData.element.id');
                                if ($forcePopup) {
                                    $rsButton->setDefaultClick('none');
                                } else {
                                    $rsButton->setDefaultClick($col['link'] === 10 ? 'main' : 'modal');
                                }
                                $rsButton->getChildren()->addTemplate($tpl);

                                $td->getContents()->addTemplate($rsButton);
                            } else {
                                $td->getContents()->addTemplate($tpl);
                            }
                        }
                    }
                }

                $event = new TableTdRenderEvent($schema, $type, $td, $col);
                $this->eventDispatcher->dispatch($event, TableTdRenderEvent::NAME);

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
