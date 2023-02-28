<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class LargeTextColumn extends ListBaseColumn {
    public function getTemplateName(): string
    {
        return '_.AppBundle.LargeTextRoColumn';
    }
}