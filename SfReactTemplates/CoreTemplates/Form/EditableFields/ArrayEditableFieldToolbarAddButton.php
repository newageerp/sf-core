<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\Template\Template;

class ArrayEditableFieldToolbarAddButton extends Template
{
    protected string $schema = '';
    
    public function __construct(string $schema)
    {
        $this->schema = $schema;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['schema'] = $this->getSchema();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'form.editable.arrayfield.toolbar.addButton';
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
