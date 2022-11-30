<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfReactTemplates\Template\Template;

class MenuDivider extends Template
{
    public function getProps(): array
    {
        return [
            'type' => 'menu-divider'
        ];
    }

    public function getTemplateName(): string
    {
        return 'main-menu.menu-divider';
    }
}
