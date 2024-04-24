<?php

namespace Newageerp\SfReactTemplates\AppTemplates\Window\MainHeader;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\CoreTemplates\MainToolbar\MainToolbarTitle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MainHeaderListener implements EventSubscriberInterface
{
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }


    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('App-Window-MainHeader')) {
            $title = '';

            $mainHeaderTitleEvent = new MainHeaderTitleEvent(
                $title,
                $event->getData()
            );
            $this->eventDispatcher->dispatch($mainHeaderTitleEvent, MainHeaderTitleEvent::NAME);
            $title = $mainHeaderTitleEvent->getTitle();

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
