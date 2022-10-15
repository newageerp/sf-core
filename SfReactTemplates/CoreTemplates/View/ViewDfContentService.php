<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\ViewFormsUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\ArrayDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\AudioDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\BoolDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\ColorDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\CustomDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\DateDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\DateTimeDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\EnumMultiNumberDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\EnumMultiTextDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\EnumNumberDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\EnumTextDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\FileDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\FileMultipleDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\FloatDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\ImageDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\LargeTextDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\NumberDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\ObjectDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\StatusDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\StringArrayDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields\StringDfRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormFieldLabel;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormFieldSeparator;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormFieldTagCloud;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormHint;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormLabel;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoForm;
use Newageerp\SfReactTemplates\CoreTemplates\Form\Rows\CompactRow;
use Newageerp\SfReactTemplates\CoreTemplates\Form\Rows\WideRow;
use Newageerp\SfReactTemplates\CoreTemplates\Layout\FlexRow;
use Newageerp\SfReactTemplates\CoreTemplates\View\ViewFormContent;

class ViewDfContentService
{

    protected ViewFormsUtilsV3 $viewFormsUtilsV3;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    public function __construct(
        ViewFormsUtilsV3 $viewFormsUtilsV3,
        PropertiesUtilsV3 $propertiesUtilsV3,
    ) {
        $this->viewFormsUtilsV3 = $viewFormsUtilsV3;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
    }

    public function fillFormContent(int $id, string $schema, string $type, ViewFormContent $roContent, bool $isCompact = false)
    {
        $editableForm = new RoForm(null, $isCompact);

        $viewForm = $this->viewFormsUtilsV3->getViewFormBySchemaAndType($schema, $type);

        $groupedFields = [];
        foreach ($viewForm['fields'] as $key => $field) {
            $lineGroup = isset($field['lineGroup']) && $field['lineGroup'] ? $field['lineGroup'] : 'no_line_group_' . $key;
            if (!isset($groupedFields[$lineGroup])) {
                $groupedFields[$lineGroup] = [];
            }
            $groupedFields[$lineGroup][] = $field;
        }

        foreach ($groupedFields as $fields) {
            $flexRow = null;
            if (count($fields) > 1) {
                $flexRow = new FlexRow();
                $flexRow->setClassName('tw3-gap-2');
            }

            foreach ($fields as $fieldIndex => $field) {
                if ($field['type'] === 'tagCloud') {
                    $wideRow = new WideRow();
                    $wideRow->getControlContent()->addTemplate(new FormFieldTagCloud($field['tagCloudField'], $field['tagCloudAction']));
                    $wideRow->setFieldVisibilityData([
                        'fieldKey' => 'tagCloud:' . $field['tagCloudField'] . ':' . $field['tagCloudAction'],
                        'fieldSchema' => $schema,
                    ]);

                    if ($flexRow !== null) {
                        $flexRow->getChildren()->addTemplate($wideRow);
                    } else {
                        $editableForm->getChildren()->addTemplate($wideRow);
                    }
                } else if ($field['type'] === 'label') {
                    $formLabel = new FormLabel($field['text']);
                    if ($fieldIndex === 0) {
                        $formLabel->setPaddingTop('tw3-pt-0');
                    }

                    $wideRow = new WideRow();
                    $wideRow->getControlContent()->addTemplate($formLabel);
                    $wideRow->setFieldVisibilityData([
                        'fieldKey' => 'label:' . $field['text'],
                        'fieldSchema' => $schema,
                    ]);

                    if ($flexRow !== null) {
                        $flexRow->getChildren()->addTemplate($wideRow);
                    } else {
                        $editableForm->getChildren()->addTemplate($wideRow);
                    }
                } else if ($field['type'] === 'hint') {
                    $wideRow = new WideRow();
                    $wideRow->getControlContent()->addTemplate(new FormHint($field['text']));
                    $wideRow->setFieldVisibilityData([
                        'fieldKey' => 'hint:' . $field['text'],
                        'fieldSchema' => $schema,
                    ]);

                    if ($flexRow !== null) {
                        $flexRow->getChildren()->addTemplate($wideRow);
                    } else {
                        $editableForm->getChildren()->addTemplate($wideRow);
                    }
                } else if ($field['type'] === 'separator') {
                    $editableForm->getChildren()->addTemplate(new FormFieldSeparator());
                } else {
                    $hideLabel = false;
                    if (isset($field['hideLabel'])) {
                        $hideLabel = $field['hideLabel'];
                    }

                    $title = '';
                    if (isset($field['customTitle']) && $field['customTitle']) {
                        $title = $field['customTitle'];
                    } else if (isset($field['titlePath']) && $field['titlePath']) {
                        $prop = $this->propertiesUtilsV3->getPropertyForPath($field['titlePath']);
                        if ($prop) {
                            $title = $prop['title'];
                        }
                    } else {
                        $pathArray = explode(".", $field['path']);
                        $level1Path = $pathArray[0] . '.' . $pathArray[1];
                        $prop = $this->propertiesUtilsV3->getPropertyForPath($level1Path);
                        if ($prop) {
                            $title = $prop['title'];
                        }
                    }

                    $wideRow = $isCompact ? new CompactRow() : new WideRow();
                    $wideRow->setLabelClassName(isset($field['labelClassName']) ? $field['labelClassName'] : '');
                    $wideRow->setControlClassName(isset($field['inputClassName']) ? $field['inputClassName'] : '');
                    if (!$hideLabel) {
                        $label = new FormFieldLabel($title);
                        if ($isCompact) {
                            $label->setWidth('tw3-w-full');
                        }
                        $wideRow->getLabelContent()->addTemplate($label);
                    }

                    $pathArray = explode(".", $field['path']);
                    $level1Path = $pathArray[0] . '.' . $pathArray[1];

                    if (isset($field['componentName']) && $field['componentName']) {
                        $wideRow->getControlContent()->addTemplate(
                            new CustomDfRoField(
                                $field['path'],
                                $id,
                                $field['componentName']
                            )
                        );
                    } else {
                        $prop = $this->propertiesUtilsV3->getPropertyForPath($level1Path);
                        if ($prop) {
                            $naeType = $this->propertiesUtilsV3->getPropertyNaeType($prop, $field);
                            if ($naeType === 'array') {
                                [$tabSchema, $tabType] = explode(':', $field['arrayRelTab']);

                                $wideRow->getControlContent()->addTemplate(
                                    new ArrayDfRoField(
                                        $field['path'],
                                        $id,
                                        $tabSchema,
                                        $tabType,
                                    )
                                );
                            }
                            if ($naeType === 'audio') {
                                $wideRow->getControlContent()->addTemplate(new AudioDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'bool') {
                                $wideRow->getControlContent()->addTemplate(new BoolDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'color') {
                                $wideRow->getControlContent()->addTemplate(new ColorDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'date') {
                                $wideRow->getControlContent()->addTemplate(new DateDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'datetime') {
                                $wideRow->getControlContent()->addTemplate(new DateTimeDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'enum_multi_number') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumMultiNumberDfRoField(
                                        $field['path'],
                                        $id,
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'enum_multi_text') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumMultiTextDfRoField(
                                        $field['path'],
                                        $id,
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'enum_number') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumNumberDfRoField(
                                        $field['path'],
                                        $id,
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'enum_text') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumTextDfRoField(
                                        $field['path'],
                                        $id,
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'file') {
                                $wideRow->getControlContent()->addTemplate(new FileDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'fileMultiple') {
                                $wideRow->getControlContent()->addTemplate(new FileMultipleDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'float') {
                                $wideRow->getControlContent()->addTemplate(new FloatDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'float4') {
                                $wideRow->getControlContent()->addTemplate(new FloatDfRoField($field['path'], $id, 4));
                            }
                            if ($naeType === 'image') {
                                $wideRow->getControlContent()->addTemplate(new ImageDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'text') {
                                $wideRow->getControlContent()->addTemplate(new LargeTextDfRoField($field['path'], $id, isset($prop['as']) ? $prop['as'] : ''));
                            }
                            if ($naeType === 'number') {
                                $wideRow->getControlContent()->addTemplate(new NumberDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'object') {
                                $objectProp = $this->propertiesUtilsV3->getPropertyForPath($field['path'], $id);

                                $objectField = new ObjectDfRoField(
                                    $field['path'],
                                    $id,
                                    $prop['entity'],
                                    $pathArray[2],
                                    $objectProp['entity']
                                );
                                $objectField->setAs($prop['as']);

                                $wideRow->getControlContent()->addTemplate($objectField);
                            }
                            if ($naeType === 'status') {
                                $wideRow->getControlContent()->addTemplate(
                                    new StatusDfRoField(
                                        $field['path'],
                                        $id,
                                        $prop['entity']
                                    )
                                );
                            }
                            if ($naeType === 'string_array') {
                                $wideRow->getControlContent()->addTemplate(new StringArrayDfRoField($field['path'], $id));
                            }
                            if ($naeType === 'string') {
                                $wideRow->getControlContent()->addTemplate(new StringDfRoField($field['path'], $id));
                            }

                            $wideRow->setFieldVisibilityData([
                                'fieldKey' => $pathArray[1],
                                'fieldSchema' => $prop['entity'],
                            ]);
                        }
                    }

                    if ($flexRow !== null) {
                        $flexRow->getChildren()->addTemplate($wideRow);
                    } else {
                        $editableForm->getChildren()->addTemplate($wideRow);
                    }
                }
            }
            if ($flexRow !== null) {
                $editableForm->getChildren()->addTemplate($flexRow);
            }
        }
        $roContent->getContent()->addTemplate($editableForm);
    }
}
