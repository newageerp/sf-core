<?php

namespace Newageerp\SfAuth\Templates;

use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Newageerp\SfReactTemplates\CoreTemplates\Modal\MenuItemWithLink;
use Newageerp\SfReactTemplates\CoreTemplates\Primitives\PrimitiveString;
use Newageerp\SfReactTemplates\CoreTemplates\Table\Table;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTd;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTh;
use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTr;
use Newageerp\SfReactTemplates\CoreTemplates\View\ViewContentListener;

class UserTemplateEvt implements EventSubscriberInterface
{
    public const SCHEMA = 'user';

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForEntity(ViewContentListener::MAINVIEWTOOLBARMORE, self::SCHEMA)) {
            $menu = new MenuItemWithLink(
                'Update password',
                '/app/nae-core/auth/password-update?id=' . $event->getData()['id'] . '&token=' . $event->getData()['_token'],
            );
            $menu->setIconName('key');
            $event->getPlaceholder()->addTemplate($menu);
        }
        if ($event->isTemplateForEntity(ViewContentListener::MAINVIEWWIDGETRIGHT, self::SCHEMA)) {
            $url = 'http://auth:3000/api/list-credentials';

            $ppData = [
                'data' => [
                    'token' => $event->getData()['_token'],
                ]
            ];

            $ch = curl_init($url);
            $payload = json_encode($ppData);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);


            if (isset($result['data'])) {
                $table = new Table();
                $event->getPlaceholder()->addTemplate($table);

                $tableTr = new TableTr();
                $table->getHead()->addTemplate($tableTr);

                $th = new TableTh();
                $th->getContents()->addTemplate(new PrimitiveString('Login'));
                $tableTr->getContents()->addTemplate($th);

                $th = new TableTh();
                $th->getContents()->addTemplate(new PrimitiveString('Type'));
                $tableTr->getContents()->addTemplate($th);

                foreach ($result['data'] as $el) {
                    $tableTr = new TableTr();
                    $table->getHead()->addTemplate($tableTr);

                    $th = new TableTd();
                    $th->getContents()->addTemplate(new PrimitiveString($el['userName']));
                    $tableTr->getContents()->addTemplate($th);

                    $th = new TableTd();
                    $th->getContents()->addTemplate(new PrimitiveString($el['type']));
                    $tableTr->getContents()->addTemplate($th);
                }
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
