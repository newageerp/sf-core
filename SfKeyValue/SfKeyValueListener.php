<?php

namespace Newageerp\SfKeyValue;

use Newageerp\SfTabs\Event\InitTabsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SfKeyValueListener implements EventSubscriberInterface
{

    public function onInit(InitTabsEvent $ev)
    {
        $tabs = $ev->getTabs();

        $tabs[] = [
            "id" => "sf-key-value-tab",
            "tag" => "",
            "title" => "sf-key-value-main",
            "config" => [
                "columns" => [
                    [
                        "path" => "sk-key-value.sfKey",
                        "titlePath" => "",
                        "customTitle" => "",
                        "link" => 0
                    ],
                    [
                        "path" => "sk-key-value.sfValue",
                        "titlePath" => "",
                        "customTitle" => "",
                        "link" => 0
                    ]
                ],
                "disableCreate" => false,
                "schema" => "sk-key-value",
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
