<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormDfBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class StatusDfRoField extends FormDfBaseField
{
    protected string $schema = '';

    public function __construct(string $key, int $id, string $schema)
    {
        parent::__construct($key, $id);
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
        return 'form.dfro.statusfield';
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
