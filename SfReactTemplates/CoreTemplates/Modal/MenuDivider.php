<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuDivider extends Template
{

    public function getProps(): array
    {
        return [];
    }

    public function getTemplateName(): string
    {
        return '_.ModalBundle.MenuDivider';
    }
}
