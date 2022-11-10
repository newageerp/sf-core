<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListDataSummary extends Template
{
    protected array $summary = [];

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function getProps(): array
    {
        return [
            'summary' => $this->getSummary()
        ];
    }

    public function getTemplateName(): string
    {
        return 'list.list-data-summary';
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
