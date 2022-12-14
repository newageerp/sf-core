<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\App;

use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppListener implements EventSubscriberInterface
{

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('App')) {
            $event->getPlaceholder()->addTemplate(new App());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
