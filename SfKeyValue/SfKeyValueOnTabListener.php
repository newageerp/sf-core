<?php

namespace Newageerp\SfKeyValue;

use Newageerp\SfTabs\Event\InitTabsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SfKeyValueOnTabListener implements EventSubscriberInterface
{

    public function onInit(InitTabsEvent $ev)
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

    public static function getSubscribedEvents()
    {
        return [
            InitTabsEvent::NAME => 'onInit'
        ];
    }
}
