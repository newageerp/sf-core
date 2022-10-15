<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Table;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class TableTh extends Template
{
    protected Placeholder $contents;

    protected ?string $textAlignment = null;

    protected ?array $filter = null;

    public function __construct(?Placeholder $contents = null)
    {
        $this->contents = $contents ? $contents : new Placeholder();
    }

    public function getTemplateName(): string
    {
        return 'table.th';
    }

    public function getProps(): array
    {
        return [
            'contents' => $this->getContents()->toArray(),
            'textAlignment' => $this->getTextAlignment(),
            'filter' => $this->getFilter(),
        ];
    }


    /**
     * Get the value of textAlignment
     *
     * @return ?string
     */
    public function getTextAlignment(): ?string
    {
        return $this->textAlignment;
    }

    /**
     * Set the value of textAlignment
     *
     * @param ?string $textAlignment
     *
     * @return self
     */
    public function setTextAlignment(?string $textAlignment): self
    {
        $this->textAlignment = $textAlignment;

        return $this;
    }

    /**
     * Get the value of contents
     *
     * @return Placeholder
     */
    public function getContents(): Placeholder
    {
        return $this->contents;
    }

    /**
     * Set the value of contents
     *
     * @param Placeholder $contents
     *
     * @return self
     */
    public function setContents(Placeholder $contents): self
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get the value of filter
     *
     * @return ?array
     */
    public function getFilter(): ?array
    {
        return $this->filter;
    }

    /**
     * Set the value of filter
     *
     * @param ?array $filter
     *
     * @return self
     */
    public function setFilter(?array $filter): self
    {
        $this->filter = $filter;

        return $this;
    }
}
