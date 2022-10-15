<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class StatusRoField extends FormBaseField
{
    protected string $schema = '';

    public function __construct(string $key, string $schema)
    {
        parent::__construct($key);
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
        return 'form.ro.statusfield';
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
