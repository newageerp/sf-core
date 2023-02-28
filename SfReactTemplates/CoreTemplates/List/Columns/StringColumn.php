<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class StringColumn extends ListBaseColumn {
    public function getTemplateName(): string
    {
        return '_.AppBundle.StringRoColumn';
    }
}