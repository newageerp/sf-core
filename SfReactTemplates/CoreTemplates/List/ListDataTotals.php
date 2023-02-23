<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListDataTotals extends Template
{
    protected array $totals = [];

    public function __construct(array $totals)
    {
        $this->totals = $totals;
    }

    public function getProps(): array
    {
        return [
            'totals' => $this->getTotals(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.ListDataTotals';
    }

    /**
     * Get the value of totals
     *
     * @return array
     */
    public function getTotals(): array
    {
        return $this->totals;
    }

    /**
     * Set the value of totals
     *
     * @param array $totals
     *
     * @return self
     */
    public function setTotals(array $totals): self
    {
        $this->totals = $totals;

        return $this;
    }
}
