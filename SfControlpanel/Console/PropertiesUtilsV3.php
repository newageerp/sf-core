<?php

namespace Newageerp\SfControlpanel\Console;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfProperties\Event\InitPropertiesEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PropertiesUtilsV3
{
    protected array $properties = [];
    protected array $enumsList = [];
    protected array $statuses = [];

    protected array $propertyForSchemaCache = [];

    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EntitiesUtilsV3 $entitiesUtilsV3,
        EventDispatcherInterface $eventDispatcher,
    ) {
        $this->eventDispatcher = $eventDispatcher;

        $this->initProperties();
        $this->enumsList = LocalConfigUtils::getCpConfigFileData('enums');
        $this->statuses = LocalConfigUtils::getCpConfigFileData('statuses');

        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    protected function initProperties()
    {
        $properties = ConfigService::getConfig('properties', true);
        $ev = new InitPropertiesEvent($properties);
        $this->getEventDispatcher()->dispatch($ev, InitPropertiesEvent::NAME);

        $this->properties = $ev->getProperties();
    }

    public static function swapSchemaToI($path)
    {
        $pathA = explode(".", $path);
        $pathA[0] = 'i';
        return implode(".", $pathA);
    }

    public function getPropertyForPath(string $_path): ?array
    {
        $path = explode(".", $_path);
        $pathLen = count($path);
        if ($pathLen === 1) {
            return null;
        } else {
            $_schema = '';
            foreach ($path as $i => $pathPart) {
                if ($i === 0) {
                    $_schema = $pathPart;
                } else if ($i === $pathLen - 1) {
                    return $this->getPropertyForSchema($_schema, $pathPart);
                } else {
                    $_lastSchema = $_schema;
                    $_schema = '';
                    $prop = $this->getPropertyForSchema($_lastSchema, $pathPart);
                    if (
                        ($prop['type'] === 'array' || $prop['type'] === 'rel') &&
                        isset($prop['typeFormat']) &&
                        $prop['typeFormat']
                    ) {
                        $_schema = $prop['typeFormat'];
                    }
                }
            }
        }
        return null;
    }

    public function getPropertyTableAlignment(?array $property, ?array $column): string
    {
        if (!$property) {
            return 'text-left';
        }
        $naeType = $this->getPropertyNaeType($property, $column);

        if ($naeType === 'float' || $naeType === 'float4' || $naeType === 'number' || $naeType === 'seconds-to-time') {
            return 'text-right';
        }

        return 'text-left';
    }

    public function getPropertyForSchema(string $schema, string $key): ?array
    {
        $cacheKey = $schema . '.' . $key;
        if (isset($this->propertyForSchemaCache[$cacheKey])) {
            return $this->propertyForSchemaCache[$cacheKey];
        } else {
            $properties = $this->getPropertiesForEntitySlug($schema);

            foreach ($properties as $prop) {
                if ($prop['config']['key'] === $key) {
                    $v = $prop['config'];
                    $this->propertyForSchemaCache[$cacheKey] = $v;
                    return $v;
                }
            }
        }
        return null;
    }

    public function getPropertiesForEntitySlug(string $slug)
    {
        return array_filter(
            $this->properties,
            function ($item) use ($slug) {
                return $item['config']['entity'] === $slug;
            }
        );
    }

    public function getPropertiesForEntitySlugValues(string $slug)
    {
        return array_map(
            function ($item) {
                return $item['config'];
            },
            array_filter(
                $this->properties,
                function ($item) use ($slug) {
                    return $item['config']['entity'] === $slug;
                }
            )
        );
    }

    public function propertyHasEnum(array $prop)
    {
        $enumsList = array_filter(
            $this->enumsList,
            function ($item) use ($prop) {
                return $item['config']['entity'] === $prop['entity'] && $item['config']['property'] === $prop['key'];
            }
        );
        return count($enumsList) > 0;
    }

    public function getPropertyEnumValue($schema, $key, $fieldVal)
    {
        $prop = $this->getPropertyForSchema($schema, $key);
        $enumsList = $this->getPropertyEnumsList($prop);
        $val = "";
        foreach ($enumsList as $p) {
            if ($p['value'] === $fieldVal) {
                $val = $p['label'];
                break;
            }
        }
        return $val;
    }

    public function getPropertyStatusList(array $property)
    {
        return array_filter(
            $this->statuses,
            function ($item) use ($property) {
                return $item['config']['entity'] === $property['entity'] && $item['config']['type'] === $property['key'];
            }
        );
    }

    public function getPropertyStatusOptions(array $prop)
    {
        $statusSchema = $this->getPropertyStatusList($prop);

        $output = [];
        foreach ($statusSchema as $status) {
            $output[] = [
                'label' => $status['config']['text'],
                'value' => $status['config']['status']
            ];
        }
        return $output;
    }

    public function getPropertyEnumsList(array $prop, ?bool $addEmpty = false)
    {
        $enumsList = array_filter(
            $this->enumsList,
            function ($item) use ($prop) {
                return $item['config']['entity'] === $prop['entity'] && $item['config']['property'] === $prop['key'];
            }
        );
        $enumsData = [];
        if ($addEmpty) {
            $enumsData[] = [
                'sort' => -1000,
                'title' => '',
                'value' => '',
            ];
        }
        foreach ($enumsList as $enum) {
            $enumsData[] = [
                'sort' => $enum['config']['sort'],
                'title' => $enum['config']['title'],
                'value' => $enum['config']['value'],
            ];
        }

        usort($enumsData, function ($pdfA, $pdfB) {
            if ($pdfA['sort'] < $pdfB['sort']) {
                return -1;
            }
            if ($pdfA['sort'] > $pdfB['sort']) {
                return 1;
            }
            if ($pdfA['title'] < $pdfB['title']) {
                return -1;
            }
            if ($pdfA['title'] > $pdfB['title']) {
                return 1;
            }
            return 0;
        });

        return  array_values(array_map(
            function ($en) use ($prop) {
                $isInt = $prop['type'] === 'integer' || $prop['type'] === 'int' || $prop['type'] === 'number';
                return [
                    'label' => $en['title'],
                    'value' => $isInt ? ((int)$en['value']) : $en['value']
                ];
            },
            $enumsData
        ));
    }

    public function getPropertyNaeType(array $property, array $column = []): string
    {
        if (!isset($property['as'])) {
            $property['as'] = '';
        }
        if (!isset($property['customAs'])) {
            $property['customAs'] = '';
        }
        if (!isset($column['type'])) {
            $column['type'] = '';
        }

        if (isset($column['naeType'])) {
            return $column['naeType'];
        }

        $fieldAs = $property['customAs'] ? $property['customAs'] : $property['as'];

        $isHtmlEditor = $fieldAs === 'html-editor';

        $hasEnum = $this->propertyHasEnum($property);

        $isStatus = $fieldAs === 'status' || $column['type'] === 'status';

        $isStringArray = $property['type'] === 'array' && $property['typeFormat'] === 'string';
        $isArray = $property['type'] === 'array' && !$isStringArray;

        $isFloat = $property['type'] === 'number' && $property['typeFormat'] === 'float';
        $isNumber = $isFloat || ($property['type'] === 'integer' && !$hasEnum);

        $isBoolean = $property['type'] === 'bool' || $property['type'] === 'boolean';
        $isDate = $property['type'] === 'string' && $property['typeFormat'] === 'date';

        $isDateTime = $property['type'] === 'string' && ($property['typeFormat'] === 'datetime' || $property['typeFormat'] === 'date-time');

        $isLargeText = $property['type'] === 'string' && $property['typeFormat'] === 'text';

        $isObject = $property['type'] === 'rel';

        $isMultiString = $property['type'] === 'array' && $hasEnum;
        $isMultiNumber = $property['type'] === 'array' && $property['typeFormat'] === 'number' && $hasEnum;

        $isEnumString = $property['type'] === 'string' && $hasEnum;
        $isEnumInteger = ($property['type'] === 'integer' || $property['type'] === 'number') && $hasEnum;

        $isFile = $fieldAs === 'file';
        $isFileMultiple = $fieldAs === 'fileMultiple';
        $isColor = $fieldAs === 'color';
        $isImage = $fieldAs === 'image';
        $isAudio = $fieldAs === 'audio';

        if ($column && isset($column['customTemplate']) && $column['customTemplate']) {
            return $column['customTemplate'];
        } else if ($isHtmlEditor) {
            return 'html-editor';
        } else if ($isStatus) {
            return 'status';
        } else if ($isFile) {
            return 'file';
        } else if ($isFileMultiple) {
            return 'fileMultiple';
        } else if ($isColor) {
            return 'color';
        } else if ($isImage) {
            return 'image';
        } else if ($isAudio) {
            return 'audio';
        } else if ($isObject) {
            return 'object';
        } else if ($isStringArray) {
            return 'string_array';
        } else if ($isFloat) {
            return 'float';
        } else if ($isNumber) {
            return 'number';
        } else if ($isDate) {
            return 'date';
        } else if ($isDateTime) {
            return 'datetime';
        } else if ($isBoolean) {
            return 'bool';
        } else if ($isLargeText) {
            return 'text';
        } else if ($isMultiNumber) {
            return 'enum_multi_number';
        } else if ($isMultiString) {
            return 'enum_multi_text';
        } else if ($isEnumString) {
            return 'enum_text';
        } else if ($isEnumInteger) {
            return 'enum_number';
        } else if ($isArray) {
            return 'array';
        }
        return 'string';
    }

    public function getOldPropertyNaeTypeFromProperty(array $item, ?array $enums = null): string
    {
        $propAs = null;
        if (isset($item['config']['customAs']) && $item['config']['customAs']) {
            $propAs = $item['config']['customAs'];
        } else if ($item['config']['as']) {
            $propAs = $item['config']['as'];
        }
        if ($propAs) {
            return $propAs;
        }
        $itemForType = $item['config'];
        $itemForType['format'] = $itemForType['typeFormat'];
        if ($enums) {
            $itemForType['enum'] = $enums;
        }
        return $this->getOldPropertyNaeType($itemForType, []);
    }

    public function getOldPropertyNaeType(array $property, array $column): string
    {
        if (!isset($property['as'])) {
            $property['as'] = '';
        }
        if (!isset($property['customAs'])) {
            $property['customAs'] = '';
        }
        if (!isset($column['type'])) {
            $column['type'] = '';
        }

        $fieldAs = $property['customAs'] ? $property['customAs'] : $property['as'];

        $isStatus = $fieldAs === 'status' || $column['type'] === 'status';

        $isStringArray = $property['type'] === 'array' && $property['format'] === 'string';
        $isArray = $property['type'] === 'array' && !$isStringArray;

        $isFloat = $property['type'] === 'number' && $property['format'] === 'float';
        $isNumber = $isFloat || ($property['type'] === 'integer' && !isset($property['enum']));

        $isBoolean = $property['type'] === 'bool' || $property['type'] === 'boolean';
        $isDate = $property['type'] === 'string' && $property['format'] === 'date';

        $isDateTime = $property['type'] === 'string' && ($property['format'] === 'datetime' || $property['format'] === 'date-time');

        $isLargeText = $property['type'] === 'string' && $property['format'] === 'text';

        $isObject = $property['type'] === 'rel';

        $isMultiString = $property['type'] === 'array' && isset($property['enum']) && count($property['enum']) > 0;
        $isMultiNumber = $property['type'] === 'array' &&
            $property['format'] === 'number' &&
            isset($property['enum']) && count($property['enum']) > 0;

        $isEnumString = $property['type'] === 'string' &&
            isset($property['enum']) && count($property['enum']) > 0;
        $isEnumInteger = ($property['type'] === 'integer' || $property['type'] === 'number') && isset($property['enum']) && count($property['enum']) > 0;

        $isFile = $fieldAs === 'file';
        $isFileMultiple = $fieldAs === 'fileMultiple';
        $isColor = $fieldAs === 'color';
        $isImage = $fieldAs === 'image';
        $isAudio = $fieldAs === 'audio';
        $isHtmlEditor = $fieldAs === 'html-editor';

        if ($column && isset($column['customTemplate'])) {
            return $column['customTemplate'];
        } else if ($isHtmlEditor) {
            return 'html-editor';
        } else if ($isStatus) {
            return 'status';
        } else if ($isFile) {
            return 'file';
        } else if ($isFileMultiple) {
            return 'fileMultiple';
        } else if ($isColor) {
            return 'color';
        } else if ($isImage) {
            return 'image';
        } else if ($isAudio) {
            return 'audio';
        } else if ($isObject) {
            return 'object';
        } else if ($isStringArray) {
            return 'string_array';
        } else if ($isFloat) {
            return 'float';
        } else if ($isNumber) {
            return 'number';
        } else if ($isDate) {
            return 'date';
        } else if ($isDateTime) {
            return 'datetime';
        } else if ($isBoolean) {
            return 'bool';
        } else if ($isLargeText) {
            return 'text';
        } else if ($isMultiNumber) {
            return 'enum_multi_number';
        } else if ($isMultiString) {
            return 'enum_multi_text';
        } else if ($isEnumString) {
            return 'enum_text';
        } else if ($isEnumInteger) {
            return 'enum_number';
        } else if ($isArray) {
            return 'array';
        }
        return 'string';
    }

    public function getDefaultPropertySearchComparison(?array $property, ?array $column): string
    {
        if (!$property) {
            return 'no';
        }

        $naeType = $this->getPropertyNaeType($property, $column ?: []);

        switch ($naeType) {
            case 'enum_text':
            case 'enum_number':
            case 'bool':
            case 'status':
            case 'enum_multi_number':
            case 'enum_multi_text':
            case 'object':
                return 'enum';
                break;
            case 'fileMultiple':
            case 'file':

            case 'string_array':
            case 'array':
                return 'no';
                break;
            case 'audio':
            case 'image':
            case 'color':
            case 'text':
            case 'string':
                return 'string';
                break;

            case 'number':
            case 'float':
                return 'number';
                break;
            case 'datetime':
            case 'date':
                return 'date';
                break;
            default:
                return 'no';
        }
    }

    public function getDefaultPropertySearchOptions(?array $property, ?array $column): array
    {
        if (!$property) {
            return [];
        }

        $naeType = $this->getPropertyNaeType($property, $column ?: []);

        switch ($naeType) {
            case 'bool':
                return [
                    ['label' => 'Yes', 'value' => 1],
                    ['label' => 'No', 'value' => 0]
                ];
                break;
            case 'enum_text':
            case 'enum_number':
            case 'enum_multi_number':
            case 'enum_multi_text':
                return $this->getPropertyEnumsList($property);
            case 'status':
                $statusSchema = $this->getPropertyStatusList($property);

                $output = [
                    [
                        'label' => '',
                        'value' => ''
                    ]
                ];
                foreach ($statusSchema as $status) {
                    $output[] = [
                        'label' => $status['config']['text'],
                        'value' => $status['config']['status']
                    ];
                }
                return $output;
                break;
            case 'fileMultiple':
            case 'file':
            case 'object':
            case 'string_array':
            case 'array':
            case 'audio':
            case 'image':
            case 'color':
            case 'text':
            case 'string':
            case 'number':
            case 'float':
            case 'datetime':
            case 'date':
                return [];
            default:
                return [];
        }
    }

    public function getClassNameForPath(string $path): string
    {
        $className = "";
        $property = $this->getPropertyForPath($path);
        if ($property) {
            $className = $this->entitiesUtilsV3->getClassNameBySlug($property['typeFormat']);
        }
        return $className;
    }

    public function getPropertyTitleForPath(string $path): string
    {
        $property = $this->getPropertyForPath($path);
        return $property ? $property['title'] : '';
    }

    public function getProperties()
    {
        return $this->properties;
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
}
