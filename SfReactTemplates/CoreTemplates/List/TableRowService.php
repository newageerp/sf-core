<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\TabsUtilsV3;
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
use Newageerp\SfReactTemplates\CoreTemplates\List\EditableColumns\LargeTextEditableColumn;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTd;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTh;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTr;
use Newageerp\SfUservice\Service\UService;

class TableRowService
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected TabsUtilsV3 $tabsUtilsV3;

    protected UService $uservice;

    public function __construct(
        PropertiesUtilsV3 $propertiesUtilsV3,
        TabsUtilsV3 $tabsUtilsV3,
        UService $uservice,
        EntitiesUtilsV3 $entitiesUtilsV3
    ) {
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->tabsUtilsV3 = $tabsUtilsV3;
        $this->uservice = $uservice;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
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
                    $prop = $this->getPropertiesUtilsV3()->getPropertyForPath($level1Path);

                    if ($prop) {
                        $alignment = $this->getPropertiesUtilsV3()->getPropertyTableAlignment($prop, $col);
                        if ($alignment !== 'tw3-text-left') {
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
                            $tpl = new BoolColumn($pathArray[1]);
                        }
                        if ($naeType === 'color') {
                            $tpl = new ColorColumn($pathArray[1]);
                        }
                        if ($naeType === 'date') {
                            $tpl = new DateColumn($pathArray[1]);
                            $td->setClassName('tw3-whitespace-nowrap');
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
                            $tpl = new FloatColumn($pathArray[1]);
                        }
                        if ($naeType === 'float4') {
                            $tpl = new FloatColumn($pathArray[1], 4);
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
                            $tpl = new StringColumn($pathArray[1]);
                        }

                        if ($tpl) {
                            if (isset($col['link']) && $col['link'] > 0 && $naeType !== 'object') {
                                $rsButton = new RsButtonTemplate($prop['entity']);
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
