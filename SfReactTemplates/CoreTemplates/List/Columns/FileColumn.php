<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class FileColumn extends ListBaseColumn {
    public function getTemplateName(): string
    {
        return 'list.ro.filecolumn';
    }
}