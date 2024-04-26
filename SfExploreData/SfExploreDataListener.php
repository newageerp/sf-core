<?php

namespace Newageerp\SfExploreData;

use Newageerp\SfDefaults\Event\InitDefaultsEvent;
use Newageerp\SfEntities\Event\InitEntitiesEvent;
use Newageerp\SfProperties\Event\InitPropertiesEvent;
use Newageerp\SfTabs\Event\InitTabsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfEditForms\Event\InitEditFormsEvent;
use Newageerp\SfViewForms\Event\InitViewFormsEvent;

class SfExploreDataListener implements EventSubscriberInterface
{

    public function onTabsInit(InitTabsEvent $ev)
    {
        $tabs = $ev->getTabs();

        $ids = array_map(
            function ($item) {
                return $item['id'];
            },
            $tabs
        );

        if (!in_array('sf-explore-data-folder-tab', $ids)) {
            $tabs[] = [
                "id" => "sf-explore-data-folder-tab",
                "tag" => "",
                "title" => "sf-explore-data-folder-tab",
                "config" => [
                    "columns" => [
                        [
                            "path" => "sf-explore-data-folder.title",
                            "titlePath" => "",
                            "customTitle" => "",
                            "link" => 10
                        ],
                        [
                            "path" => "sf-explore-data-folder.sort",
                            "titlePath" => "",
                            "customTitle" => "",
                            "link" => 0
                        ]
                    ],
                    "disableCreate" => false,
                    "schema" => "sf-explore-data-folder",
                    "type" => "main",
                    "title" => "",
                    "tabGroup" => "",
                    "tabGroupTitle" => "",
                    "predefinedFilter" => "",
                    "quickSearchFilterKeys" => "",
                    "sort" => ""
                ]
            ];
        }
        if (!in_array('sf-explore-data-item-tab', $ids)) {
            $tabs[] = [
                "id" => "sf-explore-data-item-tab",
                "tag" => "",
                "title" => "sf-explore-data-item-tab",
                "config" => [
                    "columns" => [
                        [
                            "path" => "sf-explore-data-item.exploreId",
                            "titlePath" => "",
                            "customTitle" => "",
                            "link" => 10
                        ],
                        [
                            "path" => "sf-explore-data-item.folder.title",
                            "titlePath" => "sf-explore-data-item.folder",
                            "customTitle" => "",
                            "link" => 10
                        ]
                    ],
                    "disableCreate" => false,
                    "schema" => "sf-explore-data-item",
                    "type" => "main",
                    "title" => "",
                    "tabGroup" => "",
                    "tabGroupTitle" => "",
                    "predefinedFilter" => "",
                    "quickSearchFilterKeys" => "",
                    "sort" => ""
                ]
            ];
        }
        $ev->setTabs($tabs);
    }

    public function onPropertiesInit(InitPropertiesEvent $ev)
    {
        $properies = $ev->getProperties();

        $ids = array_map(
            function ($item) {
                return $item['id'];
            },
            $properies
        );

        if (!in_array('sf-explore-data-folder.title', $ids)) {
            $properies[] = [
                "id" => "sf-explore-data-folder.title",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "varchar",
                    "description" => "",
                    "entity" => "sf-explore-data-folder",
                    "isDb" => true,
                    "key" => "title",
                    "title" => "Title",
                    "type" => "string",
                    "typeFormat" => "",
                    "available_sort" => 0,
                    "available_filter" => 0,
                    "available_group" => 0,
                    "available_total" => 0
                ]
            ];
        }
        if (!in_array('sf-explore-data-item.exploreId', $ids)) {
            $properies[] = [
                "id" => "sf-explore-data-item.exploreId",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "varchar",
                    "description" => "",
                    "entity" => "sf-explore-data-item",
                    "isDb" => true,
                    "key" => "exploreId",
                    "title" => "exploreId",
                    "type" => "string",
                    "typeFormat" => "",
                    "available_sort" => 0,
                    "available_filter" => 0,
                    "available_group" => 0,
                    "available_total" => 0
                ]
            ];
        }
        if (!in_array('sf-explore-data-item.sqlData', $ids)) {
            $properies[] = [
                "id" => "sf-explore-data-item.sqlData",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "varchar",
                    "description" => "",
                    "entity" => "sf-explore-data-item",
                    "isDb" => true,
                    "key" => "sqlData",
                    "title" => "sqlData",
                    "type" => "string",
                    "typeFormat" => "text",
                    "available_sort" => 0,
                    "available_filter" => 0,
                    "available_group" => 0,
                    "available_total" => 0
                ]
            ];
        }
        if (!in_array('sf-explore-data-item.sqlCount', $ids)) {
            $properies[] = [
                "id" => "sf-explore-data-item.sqlCount",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "varchar",
                    "description" => "",
                    "entity" => "sf-explore-data-item",
                    "isDb" => true,
                    "key" => "sqlCount",
                    "title" => "sqlCount",
                    "type" => "string",
                    "typeFormat" => "text",
                    "available_sort" => 0,
                    "available_filter" => 0,
                    "available_group" => 0,
                    "available_total" => 0
                ]
            ];
        }
        if (!in_array('sf-explore-data-item.columns', $ids)) {
            $properies[] = [
                "id" => "sf-explore-data-item.columns",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "varchar",
                    "description" => "",
                    "entity" => "sf-explore-data-item",
                    "isDb" => true,
                    "key" => "columns",
                    "title" => "columns",
                    "type" => "string",
                    "typeFormat" => "text",
                    "available_sort" => 0,
                    "available_filter" => 0,
                    "available_group" => 0,
                    "available_total" => 0
                ]
            ];
        }
        if (!in_array('sf-explore-data-item.folder', $ids)) {
            $properies[] = [
                "id" => "sf-explore-data-item.folder",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "int",
                    "description" => "",
                    "entity" => "sf-explore-data-item",
                    "isDb" => true,
                    "key" => "folder",
                    "title" => "Folder",
                    "type" => "rel",
                    "typeFormat" => "sf-explore-data-folder",
                    "available_sort" => 0,
                    "available_filter" => 0,
                    "available_group" => 0,
                    "available_total" => 0
                ]
            ];
        }

        $ev->setProperties($properies);
    }

    public function onDefaultsInit(InitDefaultsEvent $ev)
    {
        $defaults = $ev->getDefaults();

        $ids = array_map(
            function ($item) {
                return $item['id'];
            },
            $defaults
        );

        if (!in_array('sf-explore-data-item', $ids)) {
            $defaults[] = [
                "id" => "sf-explore-data-item",
                "tag" => "",
                "title" => "sf-explore-data-item",
                "config" => [
                    "defaultPath" => "i.exploreId",
                    "defaultQuickSearch" => json_encode(['i.exploreId', 'i.folder.title']),
                    "defaultSort" => json_encode([[
                        'key' => 'i.exploreId',
                        'value' => 'ASC'
                    ]]),
                    "fields" => [],
                    "schema" => "sf-explore-data-item"
                ]
            ];
        }
        if (!in_array('sf-explore-data-folder', $ids)) {
            $defaults[] = [
                "id" => "sf-explore-data-folder",
                "tag" => "",
                "title" => "sf-explore-data-folder",
                "config" => [
                    "defaultPath" => "i.title",
                    "defaultQuickSearch" => json_encode(['i.title']),
                    "defaultSort" => json_encode([
                        [
                            'key' => 'i.sort',
                            'value' => 'ASC'
                        ],
                        [
                            'key' => 'i.title',
                            'value' => 'ASC'
                        ]
                    ]),
                    "fields" => [],
                    "schema" => "sf-explore-data-folder"
                ]
            ];
        }

        $ev->setDefaults($defaults);
    }

    public function onEntitiesInit(InitEntitiesEvent $ev)
    {
        $entities = $ev->getEntities();

        $ids = array_map(
            function ($item) {
                return $item['id'];
            },
            $entities
        );

        if (!in_array('sf-explore-data-item', $ids)) {
            $entities[] = [
                "id" => "sf-explore-data-item",
                "tag" => "",
                "title" => "",
                "config" => [
                    "className" => "SfExploreDataItem",
                    "slug" => "sf-explore-data-item",
                    "titleSingle" => "Explore data",
                    "titlePlural" => "Explore data",
                    "required" => "[]",
                    "scopes" => "[]"
                ]
            ];
        }
        if (!in_array('sf-explore-data-folder', $ids)) {
            $entities[] = [
                "id" => "sf-explore-data-folder",
                "tag" => "",
                "title" => "",
                "config" => [
                    "className" => "SfExploreDataFolder",
                    "slug" => "sf-explore-data-folder",
                    "titleSingle" => "Explore data folder",
                    "titlePlural" => "Explore data folder",
                    "required" => "[]",
                    "scopes" => "[]"
                ]
            ];
        }

        $ev->setEntities($entities);
    }

    public function onEditFormsInit(InitEditFormsEvent $ev)
    {
        $editForms = $ev->getEditForms();
        $ids = array_map(
            function ($item) {
                return $item['id'];
            },
            $editForms
        );

        if (!in_array('sf-explore-data-folder', $ids)) {
            $editForms[] = [
                "id" => "sf-explore-data-folder",
                "tag" => "",
                "title" => "",
                "config" => [
                    "schema" => "sf-explore-data-folder",
                    "type" => "main",
                    "title" => "",
                    "fields" => [
                        [
                            "path" => "sf-explore-data-folder.title",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ]
                    ]
                ]
            ];
        }
        if (!in_array('sf-explore-data-item', $ids)) {
            $editForms[] = [
                "id" => "sf-explore-data-item",
                "tag" => "",
                "title" => "",
                "config" => [
                    "schema" => "sf-explore-data-item",
                    "type" => "main",
                    "title" => "",
                    "fields" => [
                        [
                            "path" => "sf-explore-data-item.exploreId",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-explore-data-item.sqlData",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-explore-data-item.sqlCount",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-explore-data-item.columns",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => "",
                            "_naeType" => "string_array",
                            "componentName" => "EditFields\/JsonField"
                        ],
                        [
                            "path" => "sf-explore-data-item.folder.title",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ]
                    ]
                ]
            ];
        }

        $ev->setEditForms($editForms);
    }
    public function onViewFormsInit(InitViewFormsEvent $ev)
    {
        $viewForms = $ev->getViewForms();
        $ids = array_map(
            function ($item) {
                return $item['id'];
            },
            $viewForms
        );

        if (!in_array('sf-explore-data-folder', $ids)) {
            $viewForms[] = [
                "id" => "sf-explore-data-folder",
                "tag" => "",
                "title" => "",
                "config" => [
                    "schema" => "sf-explore-data-folder",
                    "type" => "main",
                    "title" => "",
                    "fields" => [
                        [
                            "path" => "sf-explore-data-folder.title",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ]
                    ]
                ]
            ];
        }
        if (!in_array('sf-explore-data-item', $ids)) {
            $viewForms[] = [
                "id" => "sf-explore-data-item",
                "tag" => "",
                "title" => "",
                "config" => [
                    "schema" => "sf-explore-data-item",
                    "type" => "main",
                    "title" => "",
                    "fields" => [
                        [
                            "path" => "sf-explore-data-item.exploreId",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-explore-data-item.sqlData",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-explore-data-item.sqlCount",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-explore-data-item.folder.title",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ]
                    ]
                ]
            ];
        }
        $ev->setViewForms($viewForms);
    }

    public static function getSubscribedEvents()
    {
        return [
            InitTabsEvent::NAME => 'onTabsInit',
            InitPropertiesEvent::NAME => 'onPropertiesInit',
            InitDefaultsEvent::NAME => 'onDefaultsInit',
            InitEntitiesEvent::NAME => 'onEntitiesInit',
            InitEditFormsEvent::NAME => 'onEditFormsInit',
            InitViewFormsEvent::NAME => 'onViewFormsInit',
        ];
    }
}
