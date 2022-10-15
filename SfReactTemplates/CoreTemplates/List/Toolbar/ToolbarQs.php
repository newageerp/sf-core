<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarQs extends Template
{
    protected array $fields;
    
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar.qs';
    }

    public function getProps(): array
    {
        return [
            'fields' => $this->getFields(),
        ];
    }


    /**
     * Get the value of fields
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the value of fields
     *
     * @param array $fields
     *
     * @return self
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }
}
