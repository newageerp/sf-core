<?php

namespace Newageerp\SfReactTemplates\UListTemplates\Window;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\AppTemplates\Window\MainHeader\MainHeaderTitleEvent;
use Newageerp\SfTabs\Service\SfTabsService;

class MainHeaderTitleListener implements EventSubscriberInterface
{
    protected SfTabsService $tabsService;


    public function __construct(
        SfTabsService $tabsService,
    ) {
        $this->tabsService = $tabsService;
    }

    public function onTemplate(MainHeaderTitleEvent $event)
    {
        $eventData = $event->getEventData();
        if (isset($eventData['schema']) && isset($eventData['type'])) {
            $title = $this->tabsService->getTabToolbarTitle(
                $eventData['schema'],
                $eventData['type'],
            );
            $event->setTitle($title);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            MainHeaderTitleEvent::NAME => 'onTemplate'
        ];
    }
}
