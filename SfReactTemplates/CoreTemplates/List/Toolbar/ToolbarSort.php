<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarSort extends Template
{
    protected string $schema;

    protected array $sort;

    public function __construct(string $schema, array $sort)
    {
        $this->schema = $schema;
        $this->sort = $sort;
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar.sort';
    }

    public function getProps(): array
    {
        return [
            'defaultSort' => $this->getSort(),
            'schema' => $this->getSchema(),
        ];
    }

    /**
     * Get the value of sort
     *
     * @return array
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * Set the value of sort
     *
     * @param array $sort
     *
     * @return self
     */
    public function setSort(array $sort): self
    {
        $this->sort = $sort;

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
