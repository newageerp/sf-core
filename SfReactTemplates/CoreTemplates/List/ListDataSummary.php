<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListDataSummary extends Template
{
    protected array $summary = [];

    protected string $schema = '';

    public function __construct(string $schema, array $summary)
    {
        $this->schema = $schema;
        $this->summary = $summary;
    }

    public function getProps(): array
    {
        return [
            'summary' => $this->getSummary(),
            'schema' => $this->getSchema(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.ListDataSummary';
    }

    /**
     * Get the value of summary
     *
     * @return array
     */
    public function getSummary(): array
    {
        return $this->summary;
    }

    /**
     * Set the value of summary
     *
     * @param array $summary
     *
     * @return self
     */
    public function setSummary(array $summary): self
    {
        $this->summary = $summary;

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
