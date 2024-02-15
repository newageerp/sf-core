<?php

namespace Newageerp\SfKeyValue;

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

    public static function getSubscribedEvents()
    {
        return [
            InitTabsEvent::NAME => 'onTabsInit',
            InitPropertiesEvent::NAME => 'onPropertiesInit',
        ];
    }
}
