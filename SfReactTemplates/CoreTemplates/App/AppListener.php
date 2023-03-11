<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\App;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Modal\MenuDivider;
use Newageerp\SfReactTemplates\CoreTemplates\Modal\MenuItemWithLink;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AppListener implements EventSubscriberInterface
{
    protected EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('App')) {
            $app = new App();
            $event->getPlaceholder()->addTemplate($app);

            // init
            $this->initToolbarMenu($app, $event);
            
            // templates
            $templates = [
                [
                    'placeholder' => $app->getUserSpaceWrapperLeft(),
                    'event' => 'App.UserSpaceWrapper.LeftMenu'
                ],
                [
                    'placeholder' => $app->getUserSpaceWrapperToolbarMenu(),
                    'event' => 'App.UserSpaceWrapper.ToolbarMenu'
                ],
                [
                    'placeholder' => $app->getUserSpaceWrapperToolbarButtons()->getChildren(),
                    'event' => 'App.UserSpaceWrapper.ToolbarButtons'
                ]
            ];

            foreach ($templates as $t) {
                $templateEvent = new LoadTemplateEvent($t['placeholder'], $t['event'], $event->getData());
                $this->eventDispatcher->dispatch($templateEvent, LoadTemplateEvent::NAME);
            }
        }
    }

    protected function initToolbarMenu(App $app, LoadTemplateEvent $event)
    {
        $menuItem = new MenuItemWithLink('Support', 'https://sfslt.freshdesk.com/lt/support/home');
        $app->getUserSpaceWrapperToolbarMenu()->addTemplate($menuItem);
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
