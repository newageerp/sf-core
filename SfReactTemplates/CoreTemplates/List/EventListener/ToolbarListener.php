<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\EventListener;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfControlpanel\Console\TabsUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\List\ListDataSource;
use Newageerp\SfReactTemplates\CoreTemplates\List\ListToolbar;
use Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar\ToolbarNewButton;
use Newageerp\SfReactTemplates\Event\ListCreatableEvent;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ToolbarListener implements EventSubscriberInterface
{
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('TableService.Toolbar')) {
            $eventData = $event->getData();
            /**
             * @var ListDataSource $listDataSource
             */
            $listDataSource = $event->getData()[['listDataSource']];

            // TOOLBAR LEFT
            $templateEvent = new LoadTemplateEvent(
                $listDataSource->getToolbar()->getToolbarLeft(),
                'TableService.ToolbarLeft',
                $eventData
            );
            $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME);

            // TOOLBAR RIGHT
            $templateEvent = new LoadTemplateEvent(
                $listDataSource->getToolbar()->getToolbarRight(),
                'TableService.ToolbarRight',
                $eventData
            );
            $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME);

            $templateEvent = new LoadTemplateEvent(
                $listDataSource->getToolbar()->getToolbarRight(),
                'TableService.ToolbarRight.' . $eventData['schema'],
                $eventData
            );
            $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => [
                ['onTemplate', 1000]
            ]
        ];
    }
}
