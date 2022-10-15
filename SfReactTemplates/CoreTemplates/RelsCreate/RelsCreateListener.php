<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\RelsCreate;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfReactTemplates\CoreTemplates\Buttons\MainButton;
use Newageerp\SfReactTemplates\CoreTemplates\Buttons\ToolbarButton;
use Newageerp\SfReactTemplates\CoreTemplates\Buttons\ToolbarButtonWithMenu;
use Newageerp\SfReactTemplates\CoreTemplates\Modal\Menu;
use Newageerp\SfReactTemplates\CoreTemplates\Modal\MenuItemWithCreate;
use Newageerp\SfReactTemplates\Event\LoadTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RelsCreateListener implements EventSubscriberInterface
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(EntitiesUtilsV3 $entitiesUtilsV3)
    {
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    public function onTemplate(LoadTemplateEvent $event)
    {
        if ($event->isTemplateForAnyEntity('PageMainViewElementToolbarLine2BeforeContent')) {
            $relsCreateFile = $_ENV['NAE_SFS_CP_STORAGE_PATH'] . '/rels-create.json';

            if (file_exists($relsCreateFile)) {
                $relsList = json_decode(file_get_contents($relsCreateFile), true);

                $relsForEntity = array_filter(
                    $relsList,
                    function ($item) use ($event) {
                        return $item['source'] === $event->getData()['schema'];
                    }
                );

                if (count($relsForEntity) > 0) {
                    $mainButton = new ToolbarButton('plus');

                    $menu = new Menu(true);

                    $button = new ToolbarButtonWithMenu(
                        $mainButton,
                        $menu,
                    );

                    foreach ($relsForEntity as $el) {
                        $title = isset($el['title']) && $el['title'] ? $el['title'] : $this->entitiesUtilsV3->getTitleBySlug($el['target']);
                        $item = new MenuItemWithCreate(
                            $title,
                            $event->getData()['id'],
                            $el['source'],
                            $el['target'],
                            isset($el['forcePopup']) && $el['forcePopup'],
                        );
                        if (isset($el['createOptions']) && $el['createOptions']) {
                            $item->setCreateOptions($el['createOptions']);
                        }
                        $scopes = [];
                        if (isset($el['showScopes'])) {
                            $scopes['showScopes'] = $el['showScopes'];
                        }
                        if (isset($el['hideScopes'])) {
                            $scopes['hideScopes'] = $el['hideScopes'];
                        }
                        $item->setScopes($scopes);
                        $menu->getChildren()->addTemplate($item);
                    }
                    $event->getPlaceholder()->addTemplate($button);
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
