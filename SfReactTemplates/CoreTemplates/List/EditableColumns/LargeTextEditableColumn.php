<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\EditableColumns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class LargeTextEditableColumn extends ListBaseColumn
{
    protected string $schema = '';

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['schema'] = $this->getSchema();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'list.editable.largetextcolumn';
    }

    /**
     * Get the value of schema
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @param string $schema
     *
     * @return self
     */
    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }
}
