<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class NumberColumn extends ListBaseColumn {
    public function getTemplateName(): string
    {
        return '_.AppBundle.NumberRoColumn';
    }
}