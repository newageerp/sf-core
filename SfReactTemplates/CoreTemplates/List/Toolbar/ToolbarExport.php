<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarExport extends Template
{
    protected string $schema;
    
    protected array $exports = [];

    public function __construct(string $schema, array $exports)
    {
        $this->schema = $schema;
        $this->exports = $exports;
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'exports' => $this->getExports(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar.export';
    }


    /**
     * Get the value of exports
     *
     * @return array
     */
    public function getExports(): array
    {
        return $this->exports;
    }

    /**
     * Set the value of exports
     *
     * @param array $exports
     *
     * @return self
     */
    public function setExports(array $exports): self
    {
        $this->exports = $exports;

        return $this;
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