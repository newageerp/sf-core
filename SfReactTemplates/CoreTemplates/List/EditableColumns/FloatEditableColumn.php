<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\EditableColumns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class FloatEditableColumn extends ListBaseColumn {
    protected string $schema = '';

    protected int $accuracy = 2;

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['schema'] = $this->getSchema();
        $props['accuracy'] = $this->getAccuracy();

        return $props;
    }
    
    public function getTemplateName(): string
    {
        return '_.AppBundle.FloatEditableColumn';
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


    /**
     * Get the value of accuracy
     *
     * @return int
     */
    public function getAccuracy(): int
    {
        return $this->accuracy;
    }

    /**
     * Set the value of accuracy
     *
     * @param int $accuracy
     *
     * @return self
     */
    public function setAccuracy(int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }
}