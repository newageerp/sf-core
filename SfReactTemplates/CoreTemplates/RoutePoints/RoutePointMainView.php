<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ViewContentListener implements EventSubscriberInterface
{
    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('RoutePointMainView')) {
            $toolbarTitle = new MainToolbarTitle($this->entitiesUtilsV3->getTitleBySlug($event->getData()['schema']));
            $event->getPlaceholder()->addTemplate($toolbarTitle);

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
