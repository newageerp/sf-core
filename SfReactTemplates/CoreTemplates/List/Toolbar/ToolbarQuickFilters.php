<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarQuickFilters extends Template
{
    protected array $filters;

    public function __construct(array $filters)
    {
        
        $this->filters = $filters;
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar.filters';
    }

    public function getProps(): array
    {
        return [
            'filters' => $this->getFilters(),
        ];
    }

    /**
     * Get the value of filters
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Set the value of filters
     *
     * @param array $filters
     *
     * @return self
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }
}

