<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Edit;

use Newageerp\SfControlpanel\Console\EditFormsUtilsV3;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\ArrayEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\ArrayEditableFieldToolbarAddButton;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\AudioEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\BoolEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\ColorEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\CustomField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\DateEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\DateTimeEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\EnumMultiNumberEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\EnumMultiTextEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\EnumNumberEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\EnumTextEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\FileEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\FileMultipleEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\FloatEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\HtmlEditorEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\ImageEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\LargeTextEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\NumberEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\ObjectEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\StatusEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\StringArrayEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields\StringEditableField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\EditableForm;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormFieldLabel;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormFieldSeparator;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormFieldTagCloud;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormHint;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormLabel;
use Newageerp\SfReactTemplates\CoreTemplates\Form\Rows\CompactRow;
use Newageerp\SfReactTemplates\CoreTemplates\Form\Rows\WideRow;
use Newageerp\SfReactTemplates\CoreTemplates\Form\Rows\Wide;
use Newageerp\SfReactTemplates\CoreTemplates\Form\Rows\Compact;
use Newageerp\SfReactTemplates\CoreTemplates\Layout\FlexRow;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainer;
use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainerItem;
use Newageerp\SfReactTemplates\Event\EditEnumTextEditableFieldListEvent;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Newageerp\SfReactTemplates\Event\StatusEditableOptionsEvent;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EditContentService
{
    public const EDITCONTENTFIELDCONTROLEVENT = 'App.EditContentService.FieldControl';

    protected EventDispatcherInterface $eventDispatcher;

    protected EditFormsUtilsV3 $editFormsUtilsV3;

    protected PropertiesUtilsV3 $propertiesUtilsV3;

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(
        EditFormsUtilsV3 $editFormsUtilsV3,
        PropertiesUtilsV3 $propertiesUtilsV3,
        EntitiesUtilsV3 $entitiesUtilsV3,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->editFormsUtilsV3 = $editFormsUtilsV3;
        $this->propertiesUtilsV3 = $propertiesUtilsV3;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function fillFormContent(
        string $schema,
        string $type,
        EditFormContent $editContent,
        bool $isCompact = false
    ) {

        $requiredFields = [];

        $required = $this->entitiesUtilsV3->getRequiredBySlug($schema);

        $editForm = $this->editFormsUtilsV3->getEditFormBySchemaAndType($schema, $type);

        $stepFieldsGroup = [];
        foreach ($editForm['fields'] as $key => $field) {
            $stepGroup = isset($field['stepGroup']) && $field['stepGroup'] ? $field['stepGroup'] : 'Information';
            if (!isset($stepFieldsGroup[$stepGroup])) {
                $stepFieldsGroup[$stepGroup] = [];
            }
            $stepFieldsGroup[$stepGroup][] = $field;
        }

        $stepsPlaceholders = [];
        foreach ($stepFieldsGroup as $stepFieldGroup => $stepFields) {
            $stepsPlaceholder = new Placeholder();
            $stepsPlaceholders[$stepFieldGroup] = $stepsPlaceholder;

            $groupedFields = [];
            foreach ($stepFields as $key => $field) {
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
                    $flexRow->setClassName('gap-2');
                }

                foreach ($fields as $field) {
                    if ($field['type'] === 'tagCloud') {
                        $wideRow = new WideRow();
                        $wideRow->setIsEdit(true);
                        $wideRow->getControlContent()->addTemplate(new FormFieldTagCloud($field['tagCloudField'], $field['tagCloudAction']));
                        $wideRow->setFieldVisibilityData([
                            'fieldKey' => 'tagCloud:' . $field['tagCloudField'] . ':' . $field['tagCloudAction'],
                            'fieldSchema' => $schema,
                        ]);

                        if ($flexRow !== null) {
                            $flexRow->getChildren()->addTemplate($wideRow);
                        } else {
                            $stepsPlaceholder->addTemplate($wideRow);
                        }
                    } else if ($field['type'] === 'label') {
                        $formLabel = new FormLabel($field['text']);
                        if ($fieldIndex === 0) {
                            $formLabel->setPaddingTop('pt-0');
                        }

                        $wideRow = new WideRow();
                        $wideRow->setIsEdit(true);
                        $wideRow->getControlContent()->addTemplate($formLabel);
                        $wideRow->setFieldVisibilityData([
                            'fieldKey' => 'label:' . $field['text'],
                            'fieldSchema' => $schema,
                        ]);

                        if ($flexRow !== null) {
                            $flexRow->getChildren()->addTemplate($wideRow);
                        } else {
                            $stepsPlaceholder->addTemplate($wideRow);
                        }
                    } else if ($field['type'] === 'hint') {
                        $wideRow = new WideRow();
                        $wideRow->setIsEdit(true);
                        $wideRow->getControlContent()->addTemplate(new FormHint($field['text']));
                        $wideRow->setFieldVisibilityData([
                            'fieldKey' => 'hint:' . $field['text'],
                            'fieldSchema' => $schema,
                        ]);

                        if ($flexRow !== null) {
                            $flexRow->getChildren()->addTemplate($wideRow);
                        } else {
                            $stepsPlaceholder->addTemplate($wideRow);
                        }
                    } else if ($field['type'] === 'separator') {
                        $stepsPlaceholder->addTemplate(new FormFieldSeparator());
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

                        $pathArray = explode(".", $field['path']);
                        $level1Path = $pathArray[0] . '.' . $pathArray[1];

                        $wideRow = $isCompact ? new CompactRow() : new WideRow();
                        $wideRow->setIsEdit(true);
                        if (isset($editForm['skipCheckFieldVisibility'])) {
                            $wideRow->setSkipCheckFieldVisibility($editForm['skipCheckFieldVisibility']);
                        }
                        $wideRow->setLabelClassName(isset($field['labelClassName']) ? $field['labelClassName'] : '');
                        $wideRow->setControlClassName(isset($field['inputClassName']) ? $field['inputClassName'] : '');
                        if (!$hideLabel) {
                            $label = new FormFieldLabel($title);
                            if ($isCompact) {
                                $label->setWidth('w-full');
                            }
                            if (in_array($pathArray[1], $required) || (isset($field['required']) && $field['required'])) {
                                $label->setIsRequired(true);
                                $requiredFields[] = $pathArray[1];
                            }
                            if ($prop['description']) {
                                $label->setTooltip(($prop['description']));
                            }
                            $wideRow->getLabelContent()->addTemplate($label);
                        } else if ($isCompact) {
                            $label = new FormFieldLabel("-");
                            $label->setWidth('w-full invisible');
                            $wideRow->getLabelContent()->addTemplate($label);
                        }

                        if (isset($field['componentName']) && $field['componentName']) {
                            $wideRow->getControlContent()->addTemplate(
                                new CustomField(
                                    $pathArray[1],
                                    $pathArray[0],
                                    $field['componentName']
                                )
                            );
                        } else {

                            $prop = $this->propertiesUtilsV3->getPropertyForPath($level1Path);
                            if ($prop) {
                                $naeType = $this->propertiesUtilsV3->getPropertyNaeType($prop, $field);
                                if ($naeType === 'array') {
                                    [$tabSchema, $tabType] = explode(':', $field['arrayRelTab']);

                                    $edf = new ArrayEditableField(
                                        $pathArray[1],
                                        $tabSchema,
                                        $tabType,
                                    );
                                    $edf->getToolbar()->addTemplate(
                                        new ArrayEditableFieldToolbarAddButton($tabSchema)
                                    );
                                    $wideRow->getControlContent()->addTemplate(
                                        $edf
                                    );
                                }
                                if ($naeType === 'audio') {
                                    $wideRow->getControlContent()->addTemplate(new AudioEditableField($pathArray[1]));
                                }
                                if ($naeType === 'bool') {
                                    $wideRow->getControlContent()->addTemplate(new BoolEditableField($pathArray[1]));
                                }
                                if ($naeType === 'color') {
                                    $wideRow->getControlContent()->addTemplate(new ColorEditableField($pathArray[1]));
                                }
                                if ($naeType === 'date') {
                                    $wideRow->getControlContent()->addTemplate(new DateEditableField($pathArray[1]));
                                }
                                if ($naeType === 'datetime') {
                                    $wideRow->getControlContent()->addTemplate(new DateTimeEditableField($pathArray[1]));
                                }
                                if ($naeType === 'enum_multi_number') {
                                    $wideRow->getControlContent()->addTemplate(
                                        new EnumMultiNumberEditableField(
                                            $pathArray[1],
                                            $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                        )
                                    );
                                }
                                if ($naeType === 'enum_multi_text') {
                                    $wideRow->getControlContent()->addTemplate(
                                        new EnumMultiTextEditableField(
                                            $pathArray[1],
                                            $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                        )
                                    );
                                }
                                if ($naeType === 'enum_number') {
                                    $wideRow->getControlContent()->addTemplate(
                                        new EnumNumberEditableField(
                                            $pathArray[1],
                                            $this->propertiesUtilsV3->getPropertyEnumsList($prop),
                                        )
                                    );
                                }
                                if ($naeType === 'enum_text') {
                                    $enumProperties = $this->propertiesUtilsV3->getPropertyEnumsList($prop);

                                    $event = new EditEnumTextEditableFieldListEvent($schema, $type, $pathArray[1], $enumProperties);
                                    $this->getEventDispatcher()->dispatch($event, EditEnumTextEditableFieldListEvent::NAME);

                                    $enumProperties = $event->getOptions();

                                    $wideRow->getControlContent()->addTemplate(
                                        new EnumTextEditableField(
                                            $pathArray[1],
                                            $enumProperties,
                                        )
                                    );
                                }
                                if ($naeType === 'file') {
                                    $wideRow->getControlContent()->addTemplate(new FileEditableField($pathArray[1]));
                                }
                                if ($naeType === 'fileMultiple') {
                                    $wideRow->getControlContent()->addTemplate(new FileMultipleEditableField($pathArray[1]));
                                }
                                if ($naeType === 'float') {
                                    $wideRow->getControlContent()->addTemplate(new FloatEditableField($pathArray[1]));
                                }
                                if ($naeType === 'float4') {
                                    $wideRow->getControlContent()->addTemplate(new FloatEditableField($pathArray[1], 4));
                                }
                                if ($naeType === 'image') {
                                    $wideRow->getControlContent()->addTemplate(new ImageEditableField($pathArray[1]));
                                }
                                if ($naeType === 'text') {
                                    $wideRow->getControlContent()->addTemplate(new LargeTextEditableField($pathArray[1], isset($prop['as']) ? $prop['as'] : ''));
                                }
                                if ($naeType === 'number') {
                                    $wideRow->getControlContent()->addTemplate(new NumberEditableField($pathArray[1]));
                                }
                                if ($naeType === 'object') {
                                    $objectProp = $this->propertiesUtilsV3->getPropertyForPath($field['path']);
                                    $propAs = isset($prop['customAs']) && $prop['customAs'] ? $prop['customAs'] : $prop['as'];

                                    $objectField = new ObjectEditableField(
                                        $pathArray[1],
                                        $prop['entity'],
                                        $pathArray[2],
                                        $objectProp['entity']
                                    );
                                    $objectField->setAs($propAs);
                                    if (isset($field['fieldDependency']) && $field['fieldDependency']) {
                                        $objectField->setFieldDependency($field['fieldDependency']);
                                    }
                                    if (isset($field['relKeyExtraSelect']) && $field['relKeyExtraSelect']) {
                                        if (mb_strpos($field['relKeyExtraSelect'], '[') === 0) {
                                            $objectField->setFieldExtraSelect(json_decode($field['relKeyExtraSelect'], true));
                                        } else {
                                            $objectField->setFieldExtraSelect(explode(",", $field['relKeyExtraSelect']));
                                        }
                                    }
                                    if (isset($field['allowCreateRel']) && $field['allowCreateRel']) {
                                        $objectField->setAllowCreateRel($field['allowCreateRel']);
                                    }

                                    $wideRow->getControlContent()->addTemplate($objectField);
                                }
                                if ($naeType === 'status') {
                                    $statusEditableField = new StatusEditableField($pathArray[1]);
                                    $statusOptions = $this->propertiesUtilsV3->getPropertyStatusOptions($prop);

                                    $event = new StatusEditableOptionsEvent($statusOptions, $schema, $type, $pathArray[1]);
                                    $this->getEventDispatcher()->dispatch($event, StatusEditableOptionsEvent::NAME);

                                    $statusEditableField->setOptions($event->getOptions());
                                    $wideRow->getControlContent()->addTemplate($statusEditableField);
                                }
                                if ($naeType === 'string_array') {
                                    $wideRow->getControlContent()->addTemplate(new StringArrayEditableField($pathArray[1]));
                                }
                                if ($naeType === 'string') {
                                    $propAs = isset($prop['customAs']) && $prop['customAs'] ? $prop['customAs'] : $prop['as'];

                                    $f = new StringEditableField($pathArray[1]);
                                    $f->setAs($propAs);
                                    $wideRow->getControlContent()->addTemplate($f);
                                }
                                if ($naeType === 'html-editor') {
                                    $wideRow->getControlContent()->addTemplate(new HtmlEditorEditableField($pathArray[1]));
                                }

                                $wideRow->setFieldVisibilityData([
                                    'fieldKey' => $pathArray[1],
                                    'fieldSchema' => $prop['entity'],
                                ]);
                            }
                        }


                        $event = new LoadTemplateEvent($wideRow->getControlContent(), self::EDITCONTENTFIELDCONTROLEVENT, ['path' => $field['path']]);
                        $this->getEventDispatcher()->dispatch($event, LoadTemplateEvent::NAME);

                        if ($flexRow !== null) {
                            $flexRow->getChildren()->addTemplate($wideRow);
                        } else {
                            $stepsPlaceholder->addTemplate($wideRow);
                        }
                    }
                    $fieldIndex++;
                }
                if ($flexRow !== null) {
                    $stepsPlaceholder->addTemplate($flexRow);
                }
            }

            // stepsPlaceholder
        }
        $blockContent = null;
        if (count($stepsPlaceholders) === 1) {
            $content = reset($stepsPlaceholders);
            if ($isCompact) {
                $blockContent = new Compact();
                $blockContent->getChildren()->addPlaceholder($content);
            } else {
                $blockContent = new Wide();
                $blockContent->getChildren()->addPlaceholder($content);
            }
        } else {
            $tabContainer = new TabContainer();
            foreach ($stepsPlaceholders as $title => $placeholder) {
                $item = new TabContainerItem($title);
                $tabContainer->addItem(
                    $item
                );

                if ($isCompact) {
                    $content = new Compact();
                    $content->getChildren()->addPlaceholder($placeholder);
                } else {
                    $content = new Wide();
                    $content->getChildren()->addPlaceholder($placeholder);
                }

                $item->getContent()->addTemplate($content);
            }
            $blockContent = $tabContainer;
        }

        $editContent->getContent()->addTemplate($blockContent);

        return [
            'requiredFields' => $requiredFields
        ];
    }

    /**
     * Get the value of eventDispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * Set the value of eventDispatcher
     *
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return self
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): self
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }
}
