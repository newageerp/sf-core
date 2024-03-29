<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarExport extends Template
{
    protected string $schema;

    protected array $exports = [];

    protected array $colums = [];

    protected array $summary = [];

    public function __construct(string $schema, array $exports, array $summary = [], array $colums = [])
    {
        $this->schema = $schema;
        $this->exports = $exports;
        $this->summary = $summary;
        $this->colums = $colums;
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'exports' => $this->fixExports(),
            'summary' => $this->getSummary(),
        ];
    }

    protected function fixExports()
    {
        $exports = [];
        foreach ($this->getExports() as $export) {
            if (isset($export['useMainColumns']) && $export['useMainColumns']) {
                $export['columns'] = array_map(
                    function ($item) {
                        return [
                            'path' => $item['path'],
                            'allowEdit' => false
                        ];
                    },
                    $this->colums
                );
            }
            $exports[] = $export;
        }
        return $exports;
    }


    public function getTemplateName(): string
    {
        return '_.AppBundle.ListToolbarExport';
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
}
