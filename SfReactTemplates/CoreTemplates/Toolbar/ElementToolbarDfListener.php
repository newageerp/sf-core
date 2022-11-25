<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Toolbar;

use Newageerp\SfAuth\Service\AuthService;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuItemFactory;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuItem;
use Newageerp\SfReactTemplates\CoreTemplates\MainMenu\MenuTitle;
use Newageerp\SfReactTemplates\CoreTemplates\View\ViewContentListener;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ElementToolbarDfListener implements EventSubscriberInterface
{
    protected MenuItemFactory $menuItemFactory;

    public function __construct(MenuItemFactory $menuItemFactory)
    {
        $this->menuItemFactory = $menuItemFactory;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity(ViewContentListener::MAINVIEWTOOLBARAFTER1LINE)) {
            $user = AuthService::getInstance()->getUser();

            // check if bookmarks exists
            $btn = new ToolbarBookmarkButton(
                $event->getData()['schema'],
                (int)$event->getData()['id'],
                $user->getId()
            );
            $event->getPlaceholder()->addTemplate($btn);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
