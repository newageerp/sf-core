<?php

namespace Newageerp\SfControlpanel\Controller;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\PropertiesUtilsV3;
use Newageerp\SfControlpanel\Event\FilterPropertiesEvent;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @Route(path="/app/nae-core/config-properties")
 */
class ConfigPropertiesController extends ConfigBaseController
{
    protected function getLocalStorageFile()
    {
        $file = $this->getLocalStorage() . '/properties.json';
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
        return $file;
    }

    protected function saveBuilder($data)
    {
        file_put_contents(
            $this->getLocalStorageFile(),
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * @Route(path="/listConfig", methods={"POST"})
     */
    public function listConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = ['data' => []];

        try {
            $data = json_decode(
                file_get_contents($this->getLocalStorageFile()),
                true
            );

            if ($request->get('id')) {
                $data = array_filter(
                    $data,
                    function ($item) use ($request) {
                        return $item['id'] === $request->get('id');
                    }
                );
            }
            if ($request->get('schema')) {
                $data = array_filter(
                    $data,
                    function ($item) use ($request) {
                        return $item['config']['entity'] === $request->get('schema');
                    }
                );
            }

            $output['data'] = array_values($data);
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }

    /**
     * @Route(path="/saveConfig", methods={"POST"})
     */
    public function saveConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = [];

        try {
            $item = $request->get('item');
            if (!isset($item['id']) || !$item['id']) {
                $item['id'] = Uuid::uuid4()->toString();
            }

            $isFound = false;
            $data = json_decode(
                file_get_contents($this->getLocalStorageFile()),
                true
            );
            foreach ($data as &$el) {
                if ($el['id'] === $item['id']) {
                    $el = $item;
                    $isFound = true;
                }
            }
            if (!$isFound) {
                $data[] = $item;
            }
            unset($el);

            $this->saveBuilder($data);

            $output['data'] = $item;
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }

    /**
     * @Route(path="/saveConfigKey", methods={"POST"})
     */
    public function saveConfigKey(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = [];

        try {
            $item = $request->get('item');

            $data = json_decode(
                file_get_contents($this->getLocalStorageFile()),
                true
            );
            foreach ($data as &$el) {
                if ($el['id'] === $item['id']) {
                    $el['config'][$item['key']] = $item['value'];
                }
            }
            unset($el);

            $this->saveBuilder($data);

            $output['data'] = $item;
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }

    /**
     * @Route(path="/removeConfig", methods={"POST"})
     */
    public function removeConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $output = [];

        try {
            $id = $request->get('id');

            $tmpData = json_decode(
                file_get_contents($this->getLocalStorageFile()),
                true
            );
            $data = [];
            foreach ($tmpData as $el) {
                if ($id !== $el['id']) {
                    $data[] = $el;
                }
            }

            $this->saveBuilder($data);

            $output['data'] = [];
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }

    /**
     * @Route(path="/list", methods={"POST"})
     */
    public function listProperties(Request $request, PropertiesUtilsV3 $propertiesUtilsV3, EntitiesUtilsV3 $entitiesUtilsV3)
    {
        $request = $this->transformJsonBody($request);

        $schema = $request->get('schema');

        $properties = $propertiesUtilsV3->getProperties();
        $properties = array_filter(
            $properties,
            function ($item) use ($schema) {
                return $item['config']['entity'] === $schema;
            }
        );

        if ($request->get('db') !== null) {
            $dbF = $request->get('db');
            $properties = array_filter(
                $properties,
                function ($item) use ($dbF) {
                    return $item['config']['isDb'] === $dbF;
                }
            );
        }

        $properties = array_map(
            function ($item) {
                return [
                    'title' => $item['config']['title'],
                    'key' => $item['config']['key'],
                    'type' => isset($item['config']['naeType']) ? $item['config']['naeType'] : 'NA',
                ];
            },
            $properties
        );

        return $this->json(['success' => 1, 'data' => array_values($properties)]);
    }

    /**
     * @Route(path="/for-filter", methods={"POST"})
     */
    public function getPropertiesForFilter(Request $request, PropertiesUtilsV3 $propertiesUtilsV3, EntitiesUtilsV3 $entitiesUtilsV3)
    {
        $request = $this->transformJsonBody($request);

        $schema = $request->get('schema');
        $title = $entitiesUtilsV3->getTitleBySlug($schema);

        $output = [];

        $mainProperties = $this->schemaPropertiesForFilter($schema, $propertiesUtilsV3, false);
        $output[] = [
            'id' => 'main',
            'title' => $title,
            'isActive' => true,
            'items' => array_values($mainProperties),
        ];

        $blacklist = [$schema];

        $rels = $this->getRelsForSchema($schema, $propertiesUtilsV3);
        $this->parseRels($rels, $propertiesUtilsV3, $output, '      ', 'i.', $schema, false);

        foreach ($rels as $rel) {
            if ($rel['title'] && mb_strpos($rel['title'], 'Get the value of') === false) {
                $relsRel = $this->getRelsForSchema($rel['typeFormat'], $propertiesUtilsV3);
                $this->parseRels(
                    $relsRel,
                    $propertiesUtilsV3,
                    $output,
                    '      ' . $rel['title'] . '      -      ',
                    'i.' . $rel['key'] . '.',
                    $schema,
                    $rel['type'] === 'array'
                );

                foreach ($relsRel as $rel2) {
                    if ($rel2['title'] && !in_array($rel['typeFormat'], $blacklist) && mb_strpos($rel2['title'], 'Get the value of') === false && $rel2['typeFormat'] !== $schema) {
                        $relsRel2 = $this->getRelsForSchema($rel2['typeFormat'], $propertiesUtilsV3);
                        $this->parseRels(
                            $relsRel2,
                            $propertiesUtilsV3,
                            $output,
                            '      ' . $rel['title'] . '      -      ' . $rel2['title'] . '      -      ',
                            'i.' . $rel['key'] . '.' . $rel2['key'] . '.',
                            $schema,
                            $rel['type'] === 'array' || $rel2['type'] === 'array'
                        );
                    }
                }
            }
        }

        // ListCreatableEvent START
        $filterPropertiesEvent = new FilterPropertiesEvent($output);
        $this->getEventDispatcher()->dispatch($filterPropertiesEvent, FilterPropertiesEvent::NAME);
        $output = $filterPropertiesEvent->getFields();
        // ListCreatableEvent FINISH

        return $this->json(['data' => $output]);
    }

    protected function getRelsForSchema(string $schema, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $relsRel = $this->relPropertiesForFilter($schema, $propertiesUtilsV3);
        $relsRel = array_values(
            array_filter(
                $relsRel,
                function ($property) {
                    return isset($property['isDb']) &&
                        $property['isDb'];
                }
            )
        );

        $relsArray = $this->arrayPropertiesForFilter($schema, $propertiesUtilsV3);
        $rels = array_merge($relsRel, $relsArray);

        return $rels;
    }

    protected function parseRels(
        array $rels,
        PropertiesUtilsV3 $propertiesUtilsV3,
        array &$output,
        string $extraTitle = '',
        string $extraKey = '',
        string $initialSchema = '',
        bool $parentArray = false,
    ) {
        foreach ($rels as $k => $relProperty) {
            $isArray = $parentArray || $relProperty['type'] === 'array';

            $relSchemaProperties = $this->schemaPropertiesForFilter($relProperty['typeFormat'], $propertiesUtilsV3, $isArray);

            $relProperties = [];
            foreach ($relSchemaProperties as $relSchemaProperty) {
                $key = explode(".", $relSchemaProperty['id']);
                $title = $relSchemaProperty['title'];
                $path = $extraKey . $relProperty['key'] . '.' . $key[1];

                $property = $propertiesUtilsV3->getPropertyForPath(str_replace('i.', $initialSchema . '.',  $path));

                $type = $property ? $propertiesUtilsV3->getDefaultPropertySearchComparison($property, []) : 'text';

                $relProperties[] = [
                    'id' => $path,
                    'title' => $title,
                    'type' => $type,
                    'options' => $property ? $propertiesUtilsV3->getDefaultPropertySearchOptions($property, []) : [],
                    'arrayFilter' => $relSchemaProperty['arrayFilter']
                ];
            }

            if ($relProperty['title']) {
                $relTitle = $relProperty['title'];
                if ($extraTitle) {
                    $relTitle = $extraTitle . $relProperty['title'];
                }
                $output[] = [
                    'id' => 'rel-' . $relProperty['typeFormat'] . '-' . $k . '-' . md5($extraTitle),
                    'title' => $relTitle,
                    'isActive' => false,
                    'items' => array_values($relProperties),
                    'isArray' => $isArray,
                ];
            }
        }
    }

    /**
     * @Route(path="/for-sort", methods={"POST"})
     */
    public function getPropertiesForSort(Request $request, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $request = $this->transformJsonBody($request);

        $schema = $request->get('schema');

        $schemaProperties = $this->schemaPropertiesForSort($schema, $propertiesUtilsV3);

        $rels = $this->relPropertiesForSort($schema, $propertiesUtilsV3);
        foreach ($rels as $relProperty) {
            $relSchemaProperties = $this->schemaPropertiesForSort($relProperty['typeFormat'], $propertiesUtilsV3);
            foreach ($relSchemaProperties as $relSchemaProperty) {
                $key = explode(".", $relSchemaProperty['value']);
                $title = $relProperty['title'] . ' -> ' . $relSchemaProperty['label'];
                $schemaProperties[] = [
                    'value' => 'i.' . $relProperty['key'] . '.' . $key[1],
                    'label' => $title,
                ];
            }
        }


        return $this->json(['data' => array_values($schemaProperties)]);
    }

    protected function schemaPropertiesForSort(string $schema, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $schemaProperties = array_filter(
            $propertiesUtilsV3->getPropertiesForEntitySlugValues($schema),
            function ($property) {
                return ($property['key'] !== 'id' &&
                    isset($property['isDb']) &&
                    $property['isDb'] &&
                    isset($property['title']) &&
                    $property['title'] &&
                    // isset($property['available']) &&
                    // $property['available']['sort'] &&
                    $property['type'] !== 'rel' &&
                    $property['type'] !== 'array'
                );
            }
        );
        $hasId = count(array_filter(
            $schemaProperties,
            function ($property) {
                return $property['key'] === 'id';
            }
        )) > 0;
        if (!$hasId) {
            array_unshift(
                $schemaProperties,
                [
                    'key' => 'id',
                    'title' => 'ID'
                ]
            );
        }

        $schemaProperties = array_map(
            function ($property) {
                return [
                    'value' => 'i.' . $property['key'],
                    'label' => $property['title'],
                ];
            },
            $schemaProperties
        );

        return $schemaProperties;
    }

    protected function schemaPropertiesForFilter(string $schema, PropertiesUtilsV3 $propertiesUtilsV3, bool $parentArray = false,)
    {
        $schemaProperties = array_filter(
            $propertiesUtilsV3->getPropertiesForEntitySlugValues($schema),
            function ($property) {
                return ($property['key'] !== 'id' &&
                    isset($property['isDb']) &&
                    $property['isDb'] &&
                    // isset($property['available']) &&
                    // $property['available']['filter'] &&
                    $property['type'] !== 'rel' &&
                    $property['type'] !== 'array'
                );
            }
        );

        $schemaProperties = array_map(
            function ($property) use ($propertiesUtilsV3, $parentArray) {
                $type = $propertiesUtilsV3->getDefaultPropertySearchComparison($property, []);
                return [
                    'id' => 'i.' . $property['key'],
                    'title' => $property['title'] ? $property['title'] : 'ID: ' . $property['key'],
                    'type' => $type,
                    'options' => $propertiesUtilsV3->getDefaultPropertySearchOptions($property, []),
                    'arrayFilter' => $parentArray
                ];
            },
            $schemaProperties
        );
        return $schemaProperties;
    }

    protected function relPropertiesForSort(string $schema, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $schemaProperties = array_filter(
            $propertiesUtilsV3->getPropertiesForEntitySlugValues($schema),
            function ($property) {
                return $property['type'] == 'rel';
            }
        );

        return $schemaProperties;
    }

    protected function relPropertiesForFilter(string $schema, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $schemaProperties = array_filter(
            $propertiesUtilsV3->getPropertiesForEntitySlugValues($schema),
            function ($property) {
                return $property['type'] == 'rel';
            }
        );

        return $schemaProperties;
    }

    protected function arrayPropertiesForFilter(string $schema, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $schemaProperties = array_filter(
            $propertiesUtilsV3->getPropertiesForEntitySlugValues($schema),
            function ($property) {
                return $property['type'] == 'array';
            }
        );

        return $schemaProperties;
    }

    /**
     * @Route ("/getAvailableColumns/{schema}", methods={"GET"})
     */
    public function getAvailableColumns(Request $request, PropertiesUtilsV3 $propertiesUtilsV3)
    {
        $properties = $propertiesUtilsV3->getPropertiesForEntitySlug($request->get('schema'));

        $data = [];
        foreach ($properties as $prop) {
            $el = [
                'id' => $prop['config']['entity'] . '.' . $prop['config']['key'],
                'head' => [
                    'title' => $prop['config']['title'] ?? $prop['config']['key']
                ],
                'body' => [
                    'template' => $prop['config']['type'],
                    'dataKey' => $prop['config']['key']
                ]

            ];
            $data[] = $el;
        }

        return $this->json($data);
    }
}
