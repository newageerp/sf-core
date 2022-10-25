<?php

namespace Newageerp\SfControlpanel\Service\Tabs;

use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;

class TabsService
{
    protected array $tabs = [];

    protected PropertiesUtilsV3 $propertiesUtils;

    public function __construct(PropertiesUtilsV3 $propertiesUtilV3)
    {
        $this->tabs = LocalConfigUtils::getCpConfigFileData('tabs');
        $this->propertiesUtilV3 = $propertiesUtilV3;
    }

    public function getTabForSchemaAndType(string $schema, string $type)
    {
        foreach ($this->tabs as $tabItem) {
            if ($tabItem['config']['schema'] === $schema && $tabItem['config']['type'] === $type) {
                return $tabItem;
            }
        }
        return null;
    }

    public function getFieldsToReturnForTab(array $tab)
    {
        $fieldsToReturn = ['id', 'scopes'];
        foreach ($tab['config']['columns'] as $field) {
            if (isset($field['path']) && $field['path']) {
                $pathA = explode(".", $field['path']);
                $path = $pathA[0] . '.' . $pathA[1];
                $fieldProperty = $this->propertiesUtilV3->getPropertyForPath($path);
                $fieldObjectProperty = null;

                if ($fieldProperty) {
                    $pathArray = explode(".", $field['path']);
                    array_shift($pathArray);
                    $objectPath = implode(".", $pathArray);
                    if ($fieldProperty['type'] !== 'array') {
                        $fieldsToReturn[] = $objectPath;
                    }
                    if (count($pathArray) >= 2) {
                        $fieldsToReturn[] = $pathA[1] . '.id';
                    }
                }
            }
        }
        $fieldsToReturn = array_values(array_unique($fieldsToReturn));
        return $fieldsToReturn;
    }
}
