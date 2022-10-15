<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;
use Newageerp\SfReactTemplates\Template\Template;

class AddSelectButton extends Template
{
    public function getProps(): array
    {
        return [];
    }
    
    public function getTemplateName(): string
    {
        return 'list.action.add-select-button';
    }
}
