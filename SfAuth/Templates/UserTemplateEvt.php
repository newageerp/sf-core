<?php

namespace Newageerp\SfAuth\Templates;

use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\CoreTemplates\Modal\MenuItemWithLink;

class UserTemplateEvt implements EventSubscriberInterface
{
    public const SCHEMA = 'user';

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForEntity('PageMainViewElementToolbarMoreMenuContent', self::SCHEMA)) {
            $menu = new MenuItemWithLink(
                'Update password',
                '/app/nae-core/auth/password-update?id=' . $event->getData()['id'] . '&token=' . $event->getData()['_token'],
            );
            $menu->setIconName('key');
            $event->getPlaceholder()->addTemplate($menu);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LoadTemplateEvent::NAME => 'onTemplate'
        ];
    }
}
