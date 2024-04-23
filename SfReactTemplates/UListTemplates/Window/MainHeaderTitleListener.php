<?php

namespace Newageerp\SfReactTemplates\UListTemplates\Window;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\AppTemplates\Window\MainHeader\MainHeaderTitleEvent;
use Newageerp\SfTabs\Service\SfTabsService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;

class MainHeaderTitleListener implements EventSubscriberInterface
{
    protected SfTabsService $tabsService;
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(
        SfTabsService $tabsService,
        EntitiesUtilsV3 $entitiesUtilsV3,
    ) {
        $this->tabsService = $tabsService;
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    public function onTemplate(MainHeaderTitleEvent $event)
    {
        $eventData = $event->getEventData();
        if (isset($eventData['schema']) && isset($eventData['type']) && isset($eventData['page'])) {
            if ($eventData['page'] === 'list') {
                $title = $this->tabsService->getTabToolbarTitle(
                    $eventData['schema'],
                    $eventData['type'],
                );
                $event->setTitle($title);
            } else if ($eventData['page'] === 'view' || $eventData['page'] === 'edit') {
                $title = $this->entitiesUtilsV3->getTitleBySlug($eventData['schema']);
                $event->setTitle($title);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            MainHeaderTitleEvent::NAME => 'onTemplate'
        ];
    }
}
