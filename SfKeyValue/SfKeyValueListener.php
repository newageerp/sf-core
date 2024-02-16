<?php

namespace Newageerp\SfKeyValue;

use Newageerp\SfDefaults\Event\InitDefaultsEvent;
use Newageerp\SfEntities\Event\InitEntitiesEvent;
use Newageerp\SfProperties\Event\InitPropertiesEvent;
use Newageerp\SfTabs\Event\InitTabsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SfKeyValueListener implements EventSubscriberInterface
{

    public function onTabsInit(InitTabsEvent $ev)
    {
        $tabs = $ev->getTabs();

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
                        "link" => 0
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
        $ev->setTabs($tabs);
    }

    public function onPropertiesInit(InitPropertiesEvent $ev)
    {
        $properies = $ev->getProperties();

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
                "available_sort" => 0,
                "available_filter" => 0,
                "available_group" => 0,
                "available_total" => 0
            ]
        ];
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
                "available_sort" => 0,
                "available_filter" => 0,
                "available_group" => 0,
                "available_total" => 0
            ]
        ];
        $ev->setProperties($properies);
    }

    public function onDefaultsInit(InitDefaultsEvent $ev)
    {
        $defaults = $ev->getDefaults();

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

        $ev->setDefaults($defaults);
    }

    public function onEntitiesInit(InitEntitiesEvent $ev)
    {
        $entities = $ev->getEntities();

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

        $ev->setEntities($entities);
    }

    public static function getSubscribedEvents()
    {
        return [
            InitTabsEvent::NAME => 'onTabsInit',
            InitPropertiesEvent::NAME => 'onPropertiesInit',
            InitDefaultsEvent::NAME => 'onDefaultsInit',
            InitEntitiesEvent::NAME => 'onEntitiesInit',
        ];
    }
}
