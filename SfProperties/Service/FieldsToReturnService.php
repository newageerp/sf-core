<?php

namespace Newageerp\SfProperties\Service;

use Newageerp\SfTabs\Service\SfTabsService;

class FieldsToReturnService
{

    protected SfTabsService $sfTabsService;

    public function __construct(SfTabsService $sfTabsService)
    {
        $this->sfTabsService = $sfTabsService;
    }

    public function generateFieldsToReturn(array $formConfig)
    {
        $fieldsToReturn = [
            'id',
            'creator.fullName',
            'doer.fullName',
            'createdAt',
            'updatedAt',
            'scopes',
            'pdfFileName',
            '_viewTitle',
            'serialNumber'
        ];

        $fields = $formConfig['fields'];

        foreach ($fields as $field) {
            if (isset($field['type']) && $field['type'] === 'field') {
                $pathArray = explode(".", $field['type']);
                array_shift($pathArray);
                $fieldPath = implode(".", $pathArray);

                if (isset($field['arrayRelTab'])) {
                    [$tabSchema, $tabType] = explode(":", $field['arrayRelTab']);
                    $tab = $this->sfTabsService->getTabBySchemaAndType($tabSchema, $tabType);

                    $tabFieldsToReturn = [];
                    foreach ($tab['columns'] as $col) {
                        $colPathArray = explode(".", $col['path']);
                        array_shift($colPathArray);
                        $colPath = implode(".", $colPathArray);

                        $tabFieldsToReturn[] = $colPath;
                    }
                    $fieldToReturn = $fieldPath . ':' . implode(",", $tabFieldsToReturn);
                    $fieldsToReturn[] = $fieldToReturn;
                } else {
                    $fieldsToReturn[] = $fieldPath;
                }

                if (isset($field['extraFieldsToReturn'])) {
                    $extraFields = json_decode($field['extraFieldsToReturn'], true);
                    if ($extraFields) {
                        $fieldsToReturn = array_merge($fieldsToReturn, $extraFields);
                    }
                }
                if (isset($field['relKeyExtraSelect'])) {
                    $extraFields = explode(",", $field['relKeyExtraSelect']);
                    if ($extraFields) {
                        $fieldsToReturn = array_merge($fieldsToReturn, $extraFields);
                    }
                }
                // dependencies
            }
        }

        if (isset($formConfig['fieldsToReturn'])) {
            $fieldsToReturn = array_merge($fieldsToReturn, $formConfig['fieldsToReturn']);
        }

        return $fieldsToReturn;
    }
}
