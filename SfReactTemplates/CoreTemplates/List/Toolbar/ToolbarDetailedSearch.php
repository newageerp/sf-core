<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarDetailedSearch extends Template
{
    protected string $schema;
    
    public function __construct(string $schema)
    {
        $this->schema = $schema;
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar.detailed-search';
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