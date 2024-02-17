<?php

namespace Newageerp\SfDevMenu;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuItem;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuItemFactory;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuTitle;

class LeftMenuTemplateEvt implements EventSubscriberInterface
{
    protected MenuItemFactory $menuItemFactory;

    public function __construct(MenuItemFactory $menuItemFactory)
    {
        $this->menuItemFactory = $menuItemFactory;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        $user = AuthService::getInstance()->getUser();

        if (
            isset($_COOKIE['DEV_MENU']) &&
            $_COOKIE['DEV_MENU'] === 'true' &&
            $event->isTemplateForAnyEntity('App.UserSpaceWrapper.LeftMenu')
        ) {
            // DEV menu
            $menuTitle = new MenuTitle('DEV menu');
            $event->getPlaceholder()->addTemplate($menuTitle);

            $event->getPlaceholder()->addTemplate(
                new MenuItem('Config', '/c/config')
            );

            $event->getPlaceholder()->addTemplate(
                $this->menuItemFactory->linkForTab('sf-key-value-orm')
            );
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => ['onTemplate', -256]
        ];
    }
}
