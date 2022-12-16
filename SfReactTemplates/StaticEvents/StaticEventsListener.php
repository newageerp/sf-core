<?php

namespace Newageerp\SfReactTemplates\StaticEvents;

use Newageerp\SfAuth\Service\AuthService;
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

        foreach ($this->staticEvents as $staticEventGroup => $staticEvents) {
            foreach ($staticEvents as $staticEvent) {
                if ($staticEvent['name'] === $eventName) {
                    $tpl = new CustomPluginTemplate(
                        $staticEvent['template'],
                        $this->fixEventData($staticEvent['data'])
                    );
                    $event->getPlaceholder()->addTemplate($tpl);
                }
            }
        }
    }

    protected function fixEventData(array $data, array $eventData)
    {
        $_data = [];
        foreach ($data as $key => $el) {
            if (mb_strpos($el, '_eventData') !== false) {
                $keyPart = explode('.', $el);
                $_data[$key] = $eventData[$keyPart[1]];
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
