<?php

namespace Newageerp\SfReactTemplates\StaticEvents;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\CustomPluginTemplate;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Newageerp\SfReactTemplates\Event\StaticEventLoadEvent;
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

        $events = [];

        foreach ($this->staticEvents as $staticEventGroup => $staticEvents) {
            foreach ($staticEvents as $staticEvent) {
                if ($staticEvent['name'] === $eventName) {
                    if (!isset($staticEvent['sort'])) {
                        $staticEvent['sort'] = 999;
                    }
                    $events[] = $staticEvent;
                }
            }
        }

        usort($events, function ($a, $b) {
            return $a['sort'] <=> $b['sort'];
        });

        foreach ($events as $staticEvent) {
            $_event = new StaticEventLoadEvent($staticEvent);
            $this->eventDispatcher->dispatch($_event, StaticEventLoadEvent::NAME);

            $staticEvent = $_event->getStaticEvent();

            $tpl = new CustomPluginTemplate(
                $staticEvent['template'],
                $this->fixEventData($staticEvent['data'], $event->getData())
            );
            $event->getPlaceholder()->addTemplate($tpl);
        }
    }

    protected function fixEventData(array $data, array $eventData)
    {
        $_data = [];
        foreach ($data as $key => $el) {
            if (mb_strpos($el, '_eventData') !== false) {
                $keyPart = explode('.', $el);

                if ($keyPart[1] === 'id') {
                    $_data[$key] = (int)$eventData[$keyPart[1]];
                } else {
                    $_data[$key] = $eventData[$keyPart[1]];
                }
            } else if ($el === '_user.id') {
                $_data[$key] = AuthService::getInstance()->getUser()->getId();
            } else {
                $_data[$key] = $el;
            }
        }
        return $_data;
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
