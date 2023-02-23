<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarQuickFilters extends Template
{
    protected array $filters;

    protected bool $showLabels = false;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.ListToolbarQuickFilters';
    }

    public function getProps(): array
    {
        return [
            'filters' => $this->getFilters(),
            'showLabels' => $this->getShowLabels(),
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

    /**
     * Get the value of showLabels
     *
     * @return bool
     */
    public function getShowLabels(): bool
    {
        return $this->showLabels;
    }

    /**
     * Set the value of showLabels
     *
     * @param bool $showLabels
     *
     * @return self
     */
    public function setShowLabels(bool $showLabels): self
    {
        $this->showLabels = $showLabels;

        return $this;
    }
}

