<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\Template\Template;

class MainButtonSaveData extends MainButton
{
    protected string $schema = '';

    public function __construct(string $key, string $schema)
    {
        $this->key = $key;
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
        return 'buttons.main-button.save-data';
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
