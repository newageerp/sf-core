<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\RoutePoints;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RoutePointMainView implements EventSubscriberInterface
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EntitiesUtilsV3 $entitiesUtilsV3, EventDispatcherInterface $eventDispatcher)
    {
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('RoutePointMainView')) {
            $wEvent = new LoadTemplateEvent(
                $event->getPlaceholder(),
                'PageMainView',
                $event->getData(),
            );
            $this->eventDispatcher->dispatch($wEvent, LoadTemplateEvent::NAME);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
