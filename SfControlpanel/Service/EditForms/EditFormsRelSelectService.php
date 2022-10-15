<?php

namespace Newageerp\SfControlpanel\Service\EditForms;

use Newageerp\SfControlpanel\Console\EntitiesUtils;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Console\PropertiesUtils;
use Newageerp\SfControlpanel\Console\Utils;
use Newageerp\SfControlpanel\Service\TemplateService;

class EditFormsRelSelectService
{
    protected PropertiesUtils $propertiesUtils;

    protected EntitiesUtils $entitiesUtils;

    public function __construct(
        PropertiesUtils $propertiesUtils,
        EntitiesUtils $entitiesUtils
    ) {
        $this->propertiesUtils = $propertiesUtils;
        $this->entitiesUtils = $entitiesUtils;
    }

    public function generate()
    {
//         $tService = new TemplateService('v2/edit-forms/rels/edit-forms-rel-select.html.twig');
//         $tServiceSearch = new TemplateService('v2/edit-forms/rels/edit-forms-rel-select-search.html.twig');

//         $editItems = LocalConfigUtils::getCpConfigFileData('edit');

//         foreach ($editItems as $editItem) {
//             foreach ($editItem['config']['fields'] as $fieldIndex => $field) {
//                 $pathA = explode(".", $field['path']);
//                 if (count($pathA) < 3) {
//                     continue;
//                 }
//                 $path = $pathA[0] . '.' . $pathA[1];

//                 $fullPathProperty = $this->propertiesUtils->getPropertyForPath($field['path']);

//                 $fieldProperty = $this->propertiesUtils->getPropertyForPath($path);
//                 if (!$fieldProperty) {
//                     continue;
//                 }
//                 $type = $this->propertiesUtils->getPropertyNaeType($fieldProperty, $field);

//                 if ($type === 'object') {
//                     $relPathArray = explode(".", $field['path']);
//                     array_shift($relPathArray);
//                     array_shift($relPathArray);
//                     array_unshift($relPathArray, $fieldProperty['format']);
//                     $objectRelPath = implode(".", $relPathArray);

//                     $fieldObjectProperty = $this->propertiesUtils->getPropertyForPath($objectRelPath);
//                     if (!$fieldObjectProperty) {
//                         continue;
//                     }
//                     $objectSort = [];
//                     if ($fieldObjectProperty) {
//                         $objectSort = $this->entitiesUtils->getDefaultSortForSchema($fieldObjectProperty['schema']);
//                     }

//                     $extraFilter = '';
//                     $extraFilterSearch = '';
//                     if (isset($field['fieldDependency']) && $field['fieldDependency']) {
//                         [$filterKey, $filterValue] = explode(":", $field['fieldDependency']);
//                         $extraFilter =  'filters={[
//                                     {"and": [
//                                         ["' . $filterKey . '", "=", props.parentElement.' . $filterValue . ', true]
//                                     ]}
//                                 ]}
//                 ';
//                         $extraFilterSearch =  'extraFilter={
//                     {"and": [
//                         ["' . $filterKey . '", "=", props.parentElement.' . $filterValue . ', true]
//                     ]}
//                 }
// ';
//                     }

//                     $slug = $editItem['config']['schema'];
//                     $slugUc = Utils::fixComponentName($slug);
//                     $folderPath = Utils::generatedV2Path('edit-forms/' . $slugUc . '/rel-select');

//                     $compName = self::relSelectCompName($editItem, $field['path']);

//                     $isPopupSelectRelType = isset($field['popupSelectRelType']) && $field['popupSelectRelType'] !== '' ? true : false;
//                     $popupSelectRelType = $isPopupSelectRelType ? $field['popupSelectRelType'] : 'main';

//                     $service = $isPopupSelectRelType ? $tServiceSearch : $tService;

//                     $objectSchema = $fieldObjectProperty ? $fieldObjectProperty['schema'] : '';

//                     $service->writeToFileOnChanges(
//                         $folderPath . '/' . $compName . '.tsx',
//                         [
//                             'compName' => $compName,
//                             'objectSchema' => $objectSchema,
//                             'schema' => $fieldProperty ? $fieldProperty['schema'] : '',
//                             'sort' => json_encode($objectSort),
//                             'extraFilter' => $extraFilter,
//                             'extraFilterSearch' => $extraFilterSearch,
//                             'key' => $fieldObjectProperty ? $fieldObjectProperty['key'] : '',
//                             'allowCreateRel' => isset($field['allowCreateRel']) && $field['allowCreateRel'] ? true : false,
//                             'popupSelectRelType' => Utils::fixComponentName($objectSchema . '-' . $popupSelectRelType) . 'TableDataSource',
//                             'viewKey' => $fullPathProperty['key']
//                         ]
//                     );
//                 }
//             }
//         }
    }

    public static function relSelectCompName(array $editItem, string $path)
    {
        $pathA = explode(".", $path);
        $compName = 'WrongPath';
        if (count($pathA) > 2) {
            $compName = Utils::fixComponentName(
                $editItem['config']['type'] . '-' . $pathA[1] . '-' . $pathA[2]
            );
        }
        return $compName;
    }
}
