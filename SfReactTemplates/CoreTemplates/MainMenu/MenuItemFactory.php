<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfControlpanel\Console\EntitiesUtilsV3;

class MenuItemFactory
{
    protected EntitiesUtilsV3 $entitiesUtilsV3;

    public function __construct(EntitiesUtilsV3 $entitiesUtilsV3)
    {
        $this->entitiesUtilsV3 = $entitiesUtilsV3;
    }

    public function linkForTab(string $schema, ?string $type = 'main', ?string $iconName = null)
    {
        $menuItem = new MenuItem(
            $this->entitiesUtilsV3->getTitlePluralBySlug($schema),
            '/u/' . $schema . '/' . $type . '/list',
            $iconName
        );
        return $menuItem;
    }
}
