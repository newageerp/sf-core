<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Console\ViewFormsUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\ArrayRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\AudioRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\BoolRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\ColorRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\CustomField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\DateRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\DateTimeRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\EnumMultiNumberRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\EnumMultiTextRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\EnumNumberRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\EnumTextRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\FileRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\FileMultipleRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\FloatRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\ImageRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\LargeTextRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\NumberRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\ObjectRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\StatusRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\StringArrayRoField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields\StringRoField;
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

class ViewContentService
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

    public function fillFormContent(string $schema, string $type, ViewFormContent $roContent, bool $isCompact = false)
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

        $fieldIndex = 0;
        foreach ($groupedFields as $fields) {
            $flexRow = null;
            if (count($fields) > 1) {
                $flexRow = new FlexRow();
                $flexRow->setClassName('tw3-gap-2');
            }

            foreach ($fields as $field) {
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
                            new CustomField(
                                $pathArray[1],
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
                                    new ArrayRoField(
                                        $pathArray[1],
                                        $tabSchema,
                                        $tabType,
                                    )
                                );
                            }
                            if ($naeType === 'audio') {
                                $wideRow->getControlContent()->addTemplate(new AudioRoField($pathArray[1]));
                            }
                            if ($naeType === 'bool') {
                                $wideRow->getControlContent()->addTemplate(new BoolRoField($pathArray[1]));
                            }
                            if ($naeType === 'color') {
                                $wideRow->getControlContent()->addTemplate(new ColorRoField($pathArray[1]));
                            }
                            if ($naeType === 'date') {
                                $wideRow->getControlContent()->addTemplate(new DateRoField($pathArray[1]));
                            }
                            if ($naeType === 'datetime') {
                                $wideRow->getControlContent()->addTemplate(new DateTimeRoField($pathArray[1]));
                            }
                            if ($naeType === 'enum_multi_number') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumMultiNumberRoField(
                                        $pathArray[1],
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'enum_multi_text') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumMultiTextRoField(
                                        $pathArray[1],
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'enum_number') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumNumberRoField(
                                        $pathArray[1],
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'enum_text') {
                                $wideRow->getControlContent()->addTemplate(
                                    new EnumTextRoField(
                                        $pathArray[1],
                                        $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                    )
                                );
                            }
                            if ($naeType === 'file') {
                                $wideRow->getControlContent()->addTemplate(new FileRoField($pathArray[1]));
                            }
                            if ($naeType === 'fileMultiple') {
                                $wideRow->getControlContent()->addTemplate(new FileMultipleRoField($pathArray[1]));
                            }
                            if ($naeType === 'float') {
                                $wideRow->getControlContent()->addTemplate(new FloatRoField($pathArray[1]));
                            }
                            if ($naeType === 'float4') {
                                $wideRow->getControlContent()->addTemplate(new FloatRoField($pathArray[1], 4));
                            }
                            if ($naeType === 'image') {
                                $wideRow->getControlContent()->addTemplate(new ImageRoField($pathArray[1]));
                            }
                            if ($naeType === 'text') {
                                $wideRow->getControlContent()->addTemplate(new LargeTextRoField($pathArray[1], isset($prop['as']) ? $prop['as'] : ''));
                            }
                            if ($naeType === 'number') {
                                $wideRow->getControlContent()->addTemplate(new NumberRoField($pathArray[1]));
                            }
                            if ($naeType === 'object') {
                                $objectProp = $this->propertiesUtilsV3->getPropertyForPath($field['path']);

                                $objectField = new ObjectRoField(
                                    $pathArray[1],
                                    $prop['entity'],
                                    $pathArray[2],
                                    $objectProp['entity']
                                );
                                $objectField->setAs($prop['as']);

                                $wideRow->getControlContent()->addTemplate($objectField);
                            }
                            if ($naeType === 'status') {
                                $wideRow->getControlContent()->addTemplate(
                                    new StatusRoField(
                                        $pathArray[1],
                                        $prop['entity']
                                    )
                                );
                            }
                            if ($naeType === 'string_array') {
                                $wideRow->getControlContent()->addTemplate(new StringArrayRoField($pathArray[1]));
                            }
                            if ($naeType === 'string') {
                                $wideRow->getControlContent()->addTemplate(new StringRoField($pathArray[1]));
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
                $fieldIndex++;
            }
            if ($flexRow !== null) {
                $editableForm->getChildren()->addTemplate($flexRow);
            }
        }
        $roContent->getContent()->addTemplate($editableForm);
    }
}
