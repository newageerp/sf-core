<?php

namespace Newageerp\SfControlpanel\Console\In;

use Newageerp\SfControlpanel\Console\EntitiesUtils;
use Newageerp\SfControlpanel\Console\LocalConfigUtils;
use Newageerp\SfControlpanel\Console\PropertiesUtils;
use Newageerp\SfControlpanel\Console\Utils;
use Newageerp\SfControlpanel\Service\TemplateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class InFillModels extends Command
{
    protected static $defaultName = 'nae:localconfig:InFillModels';

    protected PropertiesUtils $propertiesUtils;

    protected EntitiesUtils $entitiesUtils;

    public function __construct(
        PropertiesUtils $propertiesUtils,
        EntitiesUtils   $entitiesUtils,
    ) {
        parent::__construct();
        $this->propertiesUtils = $propertiesUtils;
        $this->entitiesUtils = $entitiesUtils;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__, 2) . '/templates');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/tmp/smarty',
        ]);
        $ormjsTemplate = $twig->load('front-models/ormjs.html.twig');

        $dataCacheSocketTemplate = $twig->load('front-models/DataCacheSocketComponent.html.twig');
        $dataCacheProviderTemplate = $twig->load('front-models/DataCacheProvider.html.twig');
        $queueModelTemplate = $twig->load('front-models/QueueModel.html.twig');

        $ormSelectorsJsTemplate = new TemplateService('front-models/ormSelectorsJs.html.twig');
        $hooksTemplate = new TemplateService('v3/models/hooks.html.twig');

        $modelTemplate = file_get_contents(
            __DIR__ . '/templates/fill-models/model-template.txt'
        );

        $templates = [
            'field' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template.txt'
            ),
            'fieldDate' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-date.txt'
            ),
            'fieldEmpty' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-empty.txt'
            ),
            'fieldEnum' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-enum.txt'
            ),
            'fieldFloat' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-float.txt'
            ),
            'fieldObj' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-obj.txt'
            ),
            'fieldObjB' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-obj-b.txt'
            ),
            'fieldObjEx' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-obj-ex.txt'
            ),
            'fieldObjExB' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-obj-ex-b.txt'
            ),
            'fieldText' => file_get_contents(
                __DIR__ . '/templates/fill-models/field-template-text.txt'
            )
        ];

        $modelsDir = LocalConfigUtils::getFrontendModelsPath();

        $defaultsFile = $_ENV['NAE_SFS_CP_STORAGE_PATH'] . '/defaults.json';
        $defaultItems = json_decode(
            file_get_contents($defaultsFile),
            true
        );


        // CREATE MODELS
        $finder = new Finder();
        $files = $finder->files()->in($modelsDir)->name('*Model.js');
        $modelClasses = [];
        foreach ($files as $file) {
            $modelClasses[] = str_replace('Model.js', '', $file->getFilename());
        }
        foreach ($defaultItems as $defaultItem) {
            $schema = $this->entitiesUtils->getClassNameBySlug($defaultItem['config']['schema']);
            if (!in_array($schema, $modelClasses)) {
                $writeTo = $modelsDir . '/' . ucfirst($schema) . 'Model.js';
                file_put_contents(
                    $writeTo,
                    str_replace(
                        '|MODELNAME|',
                        $schema,
                        $modelTemplate
                    )
                );
            }
        }
        $queueModelFile = $modelsDir . '/QueueModel.js';
        if (!file_exists($queueModelFile)) {
            $queueModelContent = $queueModelTemplate->render([]);
            Utils::writeOnChanges($queueModelFile, $queueModelContent);
        }


        // ormstore
        $ormPath = $modelsDir . '/ormstore.ts';
        $ormContents = $twig->load('front-models/ormstore.html.twig')->render();
        Utils::writeOnChanges($ormPath, $ormContents);

        // FILL ORM.js
        $finder = new Finder();
        $files = $finder->files()->name('*Model.js')->in($modelsDir);
        $modelClasses = [];
        foreach ($files as $file) {
            $modelClasses[] = str_replace('Model.js', '', $file->getFilename());
        }
        sort($modelClasses);

        $modelsImport = array_map(
            function ($m) {
                return 'import ' . $m . 'Model from "./' . $m . 'Model"';
            },
            $modelClasses
        );
        $modelsList =
            array_map(
                function ($m) {
                    return $m . 'Model';
                },
                $modelClasses
            );
        $ormPath = $modelsDir . '/orm.js';

        $ormContents = $ormjsTemplate->render(
            [
                'models' => $modelClasses,
            ]
        );
        Utils::writeOnChanges($ormPath, $ormContents);

        // FILL ormSelectors
        $selectorsPath = $modelsDir . "/ormSelectors.js";

        $ormSelectorsJsTemplate->writeToFileOnChanges(
            $selectorsPath,
            [
                'models' => $modelClasses,
                'modelsSlugs' => array_map(
                    function ($m) {
                        return [
                            'cl' => $m,
                            'slug' => $this->entitiesUtils->getSlugByClassName($m)
                        ];
                    },
                    $modelClasses
                )
            ]
        );

        // FILL HOOKS
        $hooksDir = LocalConfigUtils::getFrontendHooksPath();
        $modelProperties = [];
        foreach ($modelClasses as $m) {
            if ($m === 'Queue') {
                continue;
            }
            $defaultItem = null;
            foreach ($defaultItems as $_defaultItem) {
                $schema = $this->entitiesUtils->getClassNameBySlug($_defaultItem['config']['schema']);
                if ($schema === $m) {
                    $defaultItem = $_defaultItem;
                }
            }

            $o = [
                ["key" => "id", "type" => "number"],
                ["key" => "scopes", "type" => "any"],
                ["key" => "badges", "type" => "any"],
            ];
            if (
                !(!$defaultItem ||
                    !isset($defaultItem['config']['fields'])
                    || count($defaultItem['config']['fields']) === 0
                )
            ) {
                foreach ($defaultItem['config']['fields'] as $c) {
                    $pathSplit = explode(".", $c['path']);

                    $pathA = array_slice($pathSplit, 1);
                    if (count($pathA) === 1) {
                        $path = implode(".", $pathA);
                        if ($path !== 'badges' && $path !== 'scopes') {
                            $o[] = [
                                'key' => $path,
                                'type' => $this->getTypeForProperty(implode(".", array_slice($pathSplit, 0, 2))),
                                'path' => implode(".", array_slice($pathSplit, 0, 2))
                            ];
                        }
                    } else {
                        $pathAB = array_slice($pathA, 1);
                        if (count($pathAB) === 1) {
                            $path = implode(".", $pathAB);
                            $className = $this->propertiesUtils->getClassNameForPath(
                                implode(".", array_slice($pathSplit, 0, 2)),
                            );
                            $o = $this->checkForExistingObjectKey(
                                $o,
                                $pathA[0],
                                [
                                    "key" => $path,
                                    "type" => $this->getTypeForProperty(implode(".", array_slice($pathSplit, 0, 3))),
                                    "path" => implode(".", array_slice($pathSplit, 0, 3)),
                                ],
                                $className,
                                implode(".", array_slice($pathSplit, 0, 2)),
                            );
                        } else {
                            $pathABC = array_slice($pathAB, 1);

                            if (count($pathABC) === 1) {
                                $classNameA = $this->propertiesUtils->getClassNameForPath(
                                    implode(".", array_slice($pathSplit, 0, 2)),
                                );
                                $o = $this->checkForExistingObjectKey(
                                    $o,
                                    $pathA[0],
                                    null,
                                    $classNameA,
                                    implode(".", array_slice($pathSplit, 0, 2))
                                );

                                foreach ($o as &$oB) {
                                    if ($oB['key'] === $pathA[0]) {
                                        $classNameB = $this->propertiesUtils->getClassNameForPath(
                                            implode(".", array_slice($pathSplit, 0, 3))
                                        );
                                        $path = implode(".", $pathABC);


                                        $oB['fields'] = $this->checkForExistingObjectKey(
                                            $oB['fields'],
                                            $pathAB[0],
                                            [
                                                "key" => $path,
                                                "type" => $this->getTypeForProperty(implode(".", array_slice($pathSplit, 0, 4))),
                                                "path" => implode(".", array_slice($pathSplit, 0, 4)),
                                            ],
                                            $classNameB,
                                            implode(".", array_slice($pathSplit, 0, 3))
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $modelProperties[$m] = $o;
        }

        $modelsFieldsCache = [];
        foreach ($modelClasses as $m) {
            if ($m === 'Queue') {
                continue;
            }

            $struct = [];
            $o = $modelProperties[$m];
            $struct['_viewTitle'] = 'string';

            $oFields = [
                '_viewTitle'
            ];

            foreach ($o as $el) {
                if ($el['type'] === "object") {
                    $objectFields = [];
                    if (isset($modelProperties[$el['schema']])) {
                        $objectFields = array_filter(
                            array_map(
                                function ($sf) {
                                    return $sf['key'];
                                },
                                $modelProperties[$el['schema']]
                            ),
                            function ($p) {
                                return $p !== 'id';
                            }
                        );
                    }

                    $objectStruct = [];
                    foreach ($el['fields'] as $f) {
                        if ($f['type'] === "object") {
                            $objectFieldsB = [];
                            if (isset($modelProperties[$f['schema']])) {
                                $objectFieldsB = array_filter(
                                    array_map(
                                        function ($sf) {
                                            return $sf['key'];
                                        },
                                        $modelProperties[$f['schema']]
                                    ),
                                    function ($p) {
                                        return $p !== 'id';
                                    }
                                );
                            }

                            $objectStructB = [];
                            foreach ($f['fields'] as $fB) {
                                if (!in_array($fB['key'], $objectFieldsB)) {
                                    $objectStructB[$fB['key']] = $fB['type'];
                                    $oFields[] = $el['key'] . '.' . $f['key'] . '.' . $fB['key'];
                                }
                            }

                            $objectStruct[$f['key']] = $objectStructB;
                        } else {
                            if (!in_array($f['key'], $objectFields)) {
                                $objectStruct[$f['key']] = $f['type'];

                                $oFields[] = $el['key'] . '.' . $f['key'];
                            }
                        }
                    }

                    $struct[$el['key']] = $objectStruct;
                } else {
                    $struct[$el['key']] = $el['type'] === '|ChildId|' ? 'ChildId[]' : $el['type'];

                    if ($el['type'] === "|ChildId|") {
                        $oFields[] = $el['key'] . ':id';
                    } else {
                        $oFields[] = $el['key'];
                    }
                }
            }

            $hooksTemplate->writeToFileOnChanges(
                $hooksDir . '/use' . $m . 'HookNae.tsx',
                [
                    'modelName' => $m,
                    'modelsStruct' => $struct,
                    'modelFieldsArray' => json_encode($oFields, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                ]
            );

            $modelsFieldsCache[$this->entitiesUtils->getSlugByClassName($m)] = $oFields;
        }
        file_put_contents(
            '/var/www/symfony/assets/model-fields.json',
            json_encode($modelsFieldsCache, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // ModelsCacheData
        $compDir = LocalConfigUtils::getFrontendModelsCachePath();
        $compFile = $compDir . '/ModelFields.tsx';

        $models = array_keys($modelProperties);
        $selectorsJoin = implode(
            ", ",
            array_map(
                function ($m) {
                    return 'Selector' . $m . 'Nae';
                },
                $models
            )
        );

        $componentsContent = "
import React, { Fragment } from 'react'
import { PropsId, PropsLink } from './types';
import { Hooks, functions, UI } from \"@newageerp/nae-react-ui\";
import { NaeSSchemaMap } from '../../config/NaeSSchema';
import moment from \"moment\";
import { " . $selectorsJoin . " } from '../../Components/Models/ormSelectors';
";

        foreach ($models as $m) {
            $componentsContent .= "import { use" . $m . "HookNae } from '../../Components/Hooks/use" . $m . "HookNae';" . PHP_EOL;
        }

        $componentsContent .= PHP_EOL . PHP_EOL;

        $componentsContent .= 'export const useEmptyHook = (id: number) => {
            return undefined;
        }';

        $componentsContent .= PHP_EOL . PHP_EOL;
        
        $componentsContent .= 'export const getHookForSchema = (schema: string) => {
  let selector : any = useEmptyHook;';

        foreach ($models as $m) {
            $s = $this->entitiesUtils->getSlugByClassName($m);
            $componentsContent .= '
  if (schema === \'' . $s . '\') {
    return use' . $m . 'HookNae;
  }';
        }
        $componentsContent .= '
  return selector;
}';

        $componentsContent .= PHP_EOL . PHP_EOL;

//         $componentsContent .= 'export const getSelectorForSchema = (schema: string) => {
//   let selector : any;';
//         foreach ($models as $m) {
//             $s = $this->entitiesUtils->getSlugByClassName($m);
//             $componentsContent .= "
//   if (schema === '" . $s . "') {
//     return Selector" . $m . "Nae;
//   }";
//         }
//         $componentsContent .= '
//   return selector;
// }';

//         $componentsContent .= PHP_EOL . PHP_EOL;

        // $pathByComponent = [];

        foreach ($models as $m) {
            $fields = $modelProperties[$m];

            $slug = $this->entitiesUtils->getSlugByClassName($m);

            // if ($m === 'Cargo') {
            //     var_dump($fields);
            //     exit();
            // }
            $t = $this->addFieldTemplateEmpty($m, $templates['fieldEmpty']);
            $componentsContent .= $t['content'];

            foreach ($fields as $el) {
                if (
                    $el['key'] === 'id' ||
                    $el['key'] === 'scopes' ||
                    $el['key'] === 'badges' ||
                    $el['type'] === '|ChildId|'
                ) {
                    continue;
                }

                if ($el['type'] === "object") {
                    // if ($m === 'Cargo') {
                    //     var_dump($el);
                    // }
                    foreach ($el['fields'] as $elB) {
                        if ($elB['key'] === 'id' && $elB['type'] === 'ChildId') {
                            continue;
                        }

                        if ($elB['type'] === "object") {
                            // if ($m === 'Cargo') {
                            //     var_dump($elB);
                            // }
                            foreach ($elB['fields'] as $elC) {
                                if ($elC['key'] === 'id' && $elC['type'] === 'ChildId') {
                                    continue;
                                }


                                if ($elC['type'] === "object") {
                                } else {
                                    $objectFieldsB = [];
                                    if (isset($modelProperties[$elB['schema']])) {
                                        $objectFieldsB = array_filter(
                                            array_map(
                                                function ($sf) {
                                                    return $sf['key'];
                                                },
                                                $modelProperties[$elB['schema']]
                                            ),
                                            function ($p) {
                                                return $p !== 'id';
                                            }
                                        );
                                    }

                                    if (in_array($elC['key'], $objectFieldsB)) {
                                        // $t = $this->addFieldObjExBTemplate(
                                        //     $m,
                                        //     $el['schema'],
                                        //     $el['key'],
                                        //     $elB['key'],
                                        //     $elC['key'],
                                        //     $elB['schema'],
                                        //     $templates['fieldObjExB']
                                        // );
                                        // $componentsContent .= $t['content'];
                                        // $elPath = $slug . '.' . $el['key'] . '.' . $elB['key'] . '.' . $elC['key'];
                                        // $pathByComponent[$elPath] = $t['comp'];
                                    } else {
                                        // $t = $this->addFieldObjBTemplate(
                                        //     $m,
                                        //     $el['key'],
                                        //     $elB['key'],
                                        //     $elC['key'],
                                        //     $templates['fieldObjB']
                                        // );
                                        // $componentsContent .= $t['content'];
                                        // $elPath = $slug . '.' . $el['key'] . '.' . $elB['key'] . '.' . $elC['key'];
                                        // $pathByComponent[$elPath] = $t['comp'];
                                    }
                                }
                            }
                        } else {
                            $objectFields = [];
                            if (isset($modelProperties[$el['schema']])) {
                                $objectFields = array_filter(
                                    array_map(
                                        function ($sf) {
                                            return $sf['key'];
                                        },
                                        $modelProperties[$el['schema']]
                                    ),
                                    function ($p) {
                                        return $p !== 'id';
                                    }
                                );
                            }

                            if (in_array($elB['key'], $objectFields)) {
                                // $t = $this->addFieldObjExTemplate(
                                //     $m,
                                //     $el['key'],
                                //     $elB['key'],
                                //     $el['schema'],
                                //     $templates['fieldObjEx'],
                                // );
                                // $componentsContent .= $t['content'];

                                // $elPath = $slug . '.' . $el['key'] . '.' . $elB['key'];
                                // $pathByComponent[$elPath] = $t['comp'];
                            } else {
                                // $t = $this->addFieldObjTemplate(
                                //     $m,
                                //     $el['key'],
                                //     $elB['key'],
                                //     $templates['fieldObj']
                                // );

                                // $componentsContent .= $t['content'];
                                // $elPath = $slug . '.' . $el['key'] . '.' . $elB['key'];
                                // $pathByComponent[$elPath] = $t['comp'];
                            }
                        }
                    }
                } else {
                    // $property = $this->propertiesUtils->getPropertyForPath($el['path']);

                    // if (isset($property['enum']) && count($property['enum']) > 0) {
                    //     $t = $this->addFieldTemplateEnum($m, $el['key'], $templates['fieldEnum']);
                    // } else if ($property['type'] === "string" && $property['format'] === "date") {
                    //     $t = $this->addFieldTemplateDate($m, $el['key'], $templates['fieldDate']);
                    // } else if ($property['type'] === "string" && $property['format'] === "text") {
                    //     $t = $this->addFieldTemplateText($m, $el['key'], $templates['fieldText']);
                    // } else {
                    //     $t = $this->addFieldTemplate($m, $el['key'], $templates['field']);
                    // }
                    // $componentsContent .= $t['content'];
                    // $pathByComponent[$el['path']] = $t['comp'];
                }
            }
        }

//         $componentsContent .= ' export const getFieldNaeViewByPath = (path: string, id: number) => {' . PHP_EOL;

//         foreach ($pathByComponent as $path => $comp) {
//             $componentsContent .= '
//     if (path === \'' . $path . '\') {
//         return <' . $comp . ' id={id}/>;
//     }';
//         }

//         $componentsContent .= '
// }' . PHP_EOL;

        file_put_contents($compFile, $componentsContent);

        $hasNotes = class_exists('App\Entity\Note');
        $hasUsers = class_exists('App\Entity\User');

        $hooksDir = LocalConfigUtils::getFrontendHooksPath();
        $socketCheckFilePath = $hooksDir . '/DataCacheSocketComponent.tsx';
        $socketFileContent = $dataCacheSocketTemplate->render(
            [
                'checks' => $models,
                'hasNotes' => $hasNotes,
                'hasUsers' => $hasUsers,
            ]
        );
        Utils::writeOnChanges($socketCheckFilePath, $socketFileContent);

        $socketProviderFilePath = $hooksDir . '/DataCacheProvider.tsx';
        $socketProviderFileContent = $dataCacheProviderTemplate->render(
            [
                'hasNotes' => $hasNotes,
                'hasUsers' => $hasUsers,
            ]
        );
        if (!file_exists($socketProviderFilePath)) {
            Utils::writeOnChanges($socketProviderFilePath, $socketProviderFileContent);
        }

        return Command::SUCCESS;
    }

    public function getTypeForProperty(string $path)
    {
        $property = $this->propertiesUtils->getPropertyForPath($path);
        if (!$property) {
            return "any";
        }
        if ($property['type'] === "string") {
            return "string";
        }
        if (
            $property['type'] === "integer" ||
            $property['type'] === "float" ||
            $property['type'] === "number"
        ) {
            return "number";
        }
        if ($property['type'] === "boolean") {
            return "boolean";
        }
        return "any";
    }

    protected function checkForExistingObjectKey(
        $o,
        $key,
        $field,
        $className,
        $propertyPath
    ) {
        // var_dump($key);
        // var_dump($field);
        // var_dump($className);
        // var_dump($propertyPath);

        $isFilter = count(array_filter($o, function ($o) use ($key) {
            return $o['key'] === $key;
        })) > 0;

        // var_dump($isFilter);
        // var_dump('-------');

        if ($isFilter) {
            if ($field && $field['key'] !== "id") {
                $o = array_map(
                    function ($e) use ($key, $field) {
                        if ($e['key'] === $key) {
                            $e['fields'][] = $field;
                        }
                        return $e;
                    },
                    $o
                );
            }
        } else {
            $property = $this->propertiesUtils->getPropertyForPath($propertyPath);

            $_f = [
                "schema" => $className,
                "key" => $key,
                "type" => $property && $property['type'] === "rel" ? "object" : "|ChildId|",
                "fields" => [
                    [
                        "key" => "id",
                        "type" => "number",
                    ],
                ],
            ];
            if ($field && $field['key'] !== "id") {
                $_f['fields'][] = $field;
            }
            $o[] = $_f;
        }
        return $o;
    }

    public function addFieldTemplateEmpty($model, $template): array
    {
        $key = "empty";

        $compName = $model . '' . ucfirst($key) . 'FieldNae';

        return [
            'content' => str_replace(
                ['|FIELD|', '|TEMPLATE|', '|SELECTOR|', '|SCHEMA|'],
                [
                    $key,
                    $compName,
                    'use' . $model . 'HookNae',
                    $model
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldObjExBTemplate(
        $model,
        $modelB,
        $key,
        $keyB,
        $keyC,
        $schema,
        $template
    ): array {
        $compName = $schema . ucfirst($keyC) . 'FieldNae';

        return [
            'content' => str_replace(
                ["|TEMPLATEC|", "|FIELD|", "|FIELDB|", "|TEMPLATE|", "|SELECTOR|", "|SELECTORB|"],
                [
                    $compName,
                    $key,
                    $keyB,
                    $model . ucfirst($key) . 'REL' . ucfirst($keyB,) . ucfirst($keyC) . 'FieldNae',
                    'use' . $model . 'HookNae',
                    'use' . $modelB . 'HookNae',
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldObjBTemplate(
        $model,
        $key,
        $keyB,
        $keyC,
        $template
    ): array {
        $compName = $model . ucfirst($key) . ucfirst($keyB,) . ucfirst($keyC) . 'FieldNae';

        return [
            'content' => str_replace(
                ["|FIELDC|", "|FIELDB|", "|FIELD|", "|TEMPLATE|", "|SELECTOR|"],
                [
                    $keyC,
                    $keyB,
                    $key,
                    $compName,
                    'use' . $model . 'HookNae'
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldObjExTemplate(
        $model,
        $key,
        $keyB,
        $schema,
        $template
    ): array {
        $compName = $model . ucfirst($key) . 'REL' . ucfirst($keyB,) . 'FieldNae';

        return [
            'content' => str_replace(
                [
                    "|TEMPLATEB|",
                    "|FIELD|",
                    "|TEMPLATE|",
                    "|SELECTOR|",
                ],
                [
                    $schema . ucfirst($keyB) . 'FieldNae',
                    $key,
                    $compName,
                    'use' . $model . 'HookNae',
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldObjTemplate($model, $key, $keyB, $template): array
    {
        $compName = $model . ucfirst($key) . ucfirst($keyB) . 'FieldNae';
        return [
            'content' => str_replace(
                ["|FIELDB|", "|FIELD|", "|TEMPLATE|", "|SELECTOR|"],
                [
                    $keyB, $key,
                    $compName,
                    'use' . $model . 'HookNae',
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldTemplateEnum($model, $key, $template): array
    {
        $compName = $model . ucfirst($key) . 'FieldNae';

        return [
            'content' => str_replace(
                ["|SCHEMA|", "|FIELD|", "|TEMPLATE|", "|SELECTOR|"],
                [
                    $model,
                    $key,
                    $compName,
                    'use' . $model . 'HookNae',
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldTemplateDate($model, $key, $template): array
    {
        $compName = $model . ucfirst($key) . 'FieldNae';

        return [
            'content' => str_replace(
                [
                    "|FIELD|",
                    "|TEMPLATE|",
                    "|SELECTOR|",
                    "|SCHEMA|",
                ],
                [
                    $key,
                    $compName,
                    'use' . $model . 'HookNae',
                    $model
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldTemplateText($model, $key, $template): array
    {
        $compName = $model . ucfirst($key) . 'FieldNae';

        return [
            'content' => str_replace(
                [
                    "|FIELD|",
                    "|TEMPLATE|",
                    "|SELECTOR|",
                    "|SCHEMA|",
                ],
                [
                    $key,
                    $compName,
                    'use' . $model . 'HookNae',
                    $model
                ],
                $template
            ),
            'comp' => $compName
        ];
    }

    public function addFieldTemplate($model, $key, $template): array
    {
        $compName = $model . ucfirst($key) . 'FieldNae';
        return [
            'content' => str_replace(
                [
                    "|FIELD|",
                    "|TEMPLATE|",
                    "|SELECTOR|",
                    "|SCHEMA|",
                ],
                [
                    $key,
                    $compName,
                    'use' . $model . 'HookNae',
                    $model
                ],
                $template
            ),
            'comp' => $compName
        ];
    }
}
