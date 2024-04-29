<?php

namespace Newageerp\SfKeyValue;

use Newageerp\SfDefaults\Event\InitDefaultsEvent;
use Newageerp\SfEntities\Event\InitEntitiesEvent;
use Newageerp\SfProperties\Event\InitPropertiesEvent;
use Newageerp\SfTabs\Event\InitTabsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfEditForms\Event\InitEditFormsEvent;
use Newageerp\SfViewForms\Event\InitViewFormsEvent;

class SfKeyValueListener implements EventSubscriberInterface
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

        if (!in_array('sf-key-value-orm-tab', $ids)) {
            $tabs[] = [
                "id" => "sf-key-value-orm-tab",
                "tag" => "",
                "title" => "sf-key-value-orm-main",
                "config" => [
                    "columns" => [
                        [
                            "path" => "sf-key-value-orm.sfKey",
                            "titlePath" => "",
                            "customTitle" => "",
                            "link" => 10
                        ],
                        [
                            "path" => "sf-key-value-orm.sfValue",
                            "titlePath" => "",
                            "customTitle" => "",
                            "link" => 0
                        ]
                    ],
                    "disableCreate" => false,
                    "schema" => "sf-key-value-orm",
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

        if (!in_array('sf-key-value-orm.sfKey', $ids)) {
            $properies[] = [
                "id" => "sf-key-value-orm.sfKey",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "varchar",
                    "description" => "",
                    "entity" => "sf-key-value-orm",
                    "isDb" => true,
                    "key" => "sfKey",
                    "title" => "sfKey",
                    "type" => "string",
                    "typeFormat" => "",
                ]
            ];
        }
        if (!in_array('sf-key-value-orm.sfValue', $ids)) {
            $properies[] = [
                "id" => "sf-key-value-orm.sfValue",
                "tag" => "",
                "title" => "",
                "config" => [
                    "additionalProperties" => "[]",
                    "as" => "",
                    "dbType" => "varchar",
                    "description" => "",
                    "entity" => "sf-key-value-orm",
                    "isDb" => true,
                    "key" => "sfValue",
                    "title" => "sfValue",
                    "type" => "string",
                    "typeFormat" => "",
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

        if (!in_array('sf-key-value-orm', $ids)) {
            $defaults[] = [
                "id" => "sf-key-value-orm",
                "tag" => "",
                "title" => "sf-key-value-orm",
                "config" => [
                    "defaultPath" => "i.sfKey",
                    "defaultQuickSearch" => json_encode(['i.sfKey', 'i.sfValue']),
                    "defaultSort" => json_encode([[
                        'key' => 'i.sfKey',
                        'value' => 'ASC'
                    ]]),
                    "fields" => [],
                    "schema" => "sf-key-value-orm"
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

        if (!in_array('sf-key-value-orm', $ids)) {
            $entities[] = [
                "id" => "sf-key-value-orm",
                "tag" => "",
                "title" => "",
                "config" => [
                    "className" => "SfKeyValueOrm",
                    "slug" => "sf-key-value-orm",
                    "titleSingle" => "Key-value",
                    "titlePlural" => "Key-value",
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

        if (!in_array('sf-key-value-orm', $ids)) {
            $editForms[] = [
                "id" => "sf-key-value-orm",
                "tag" => "",
                "title" => "",
                "config" => [
                    "schema" => "sf-key-value-orm",
                    "type" => "main",
                    "title" => "",
                    "fields" => [
                        [
                            "path" => "sf-key-value-orm.sfKey",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-key-value-orm.sfValue",
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

        if (!in_array('sf-key-value-orm', $ids)) {
            $viewForms[] = [
                "id" => "sf-key-value-orm",
                "tag" => "",
                "title" => "",
                "config" => [
                    "schema" => "sf-key-value-orm",
                    "type" => "main",
                    "title" => "",
                    "fields" => [
                        [
                            "path" => "sf-key-value-orm.sfKey",
                            "titlePath" => "",
                            "customTitle" => "",
                            "hideLabel" => false,
                            "type" => "field",
                            "text" => "",
                            "lineGroup" => ""
                        ],
                        [
                            "path" => "sf-key-value-orm.sfValue",
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
