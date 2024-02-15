<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\EventListener;

use Newageerp\SfReactTemplates\CoreTemplates\CustomPluginTemplate;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ToolbarLeftReloadListener implements EventSubscriberInterface
{
    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('TableService.ToolbarLeft')) {
            $event->getPlaceholder()->addTemplate(new CustomPluginTemplate(
                '_.AppBundle.ListToolbarReload',
                []
            ));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => [
                ['onTemplate', 800]
            ]
        ];
    }

}
