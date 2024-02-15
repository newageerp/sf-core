<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;
use Newageerp\SfTabs\Service\SfTabsService;

class MenuItemFactory
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    protected SfTabsService $tabsUtilsV3;

    public function __construct(EntitiesUtilsV3 $entitiesUtilsV3, SfTabsService $tabsUtilsV3)
    {
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
        $this->tabsUtilsV3 = $tabsUtilsV3;
    }

    public function linkForTab(string $schema, ?string $type = 'main', ?string $iconName = null)
    {
        $tab = $this->tabsUtilsV3->getTabBySchemaAndType($schema, $type);

        $title = isset($tab['title']) && $tab['title'] ? $tab['title'] : $this->entitiesUtilsV3->getTitlePluralBySlug($schema);

        $menuItem = new MenuItem(
            $title,
            '/u/' . $schema . '/' . $type . '/list',
            $iconName
        );
        return $menuItem;
    }
}
