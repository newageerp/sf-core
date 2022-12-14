<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\App;

use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AppListener implements EventSubscriberInterface
{

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('App')) {
            $app = new App();
            $event->getPlaceholder()->addTemplate($app);

            $userSpaceWrapperLeft = new LoadTemplateEvent($app->getUserSpaceWrapperLeft(), 'App.UserSpaceWrapper.LeftMenu', $event->getData());
            $this->eventDispatcher->dispatch($userSpaceWrapperLeft, LoadTemplateEvent::NAME);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
