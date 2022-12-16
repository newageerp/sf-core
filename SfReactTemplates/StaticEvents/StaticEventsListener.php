<?php

namespace Newageerp\SfReactTemplates\StaticEvents;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\CustomPluginTemplate;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class StaticEventsListener implements EventSubscriberInterface
{
    protected EventDispatcherInterface $eventDispatcher;
    protected array $staticEvents = [];

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->staticEvents = LocalConfigUtilsV3::getCpConfigFileData('static-events');
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        $eventName = $event->getTemplateName();

        foreach ($this->staticEvents as $staticEvent) {
            if ($staticEvent['name'] === $eventName) {
                $tpl = new CustomPluginTemplate(
                    $staticEvent['template'],
                    $staticEvent['data']
                );
                $event->getPlaceholder()->addTemplate($tpl);
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
