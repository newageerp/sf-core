<?php

namespace Newageerp\SfReactTemplates\AppTemplates\Window\MainHeader;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;

class MainHeaderListener implements EventSubscriberInterface
{
    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('App\\Window\\MainHeader')) {
            $title = 'XXX';

            $tpl = new MainToolbarTitle($title);
            $event->getPlaceholder()->addTemplate($tpl);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }

}
