<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class FloatColumn extends ListBaseColumn {
    public function getTemplateName(): string
    {
        return 'list.ro.floatcolumn';
    }
}