<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Table;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class TableTh extends Template
{
    protected Placeholder $contents;

    protected ?string $textAlignment = null;

    protected ?array $filter = null;

    protected ?array $sort = null;

    public function __construct(?Placeholder $contents = null)
    {
        $this->contents = $contents ? $contents : new Placeholder();
    }

    public function getTemplateName(): string
    {
        return '_.LayoutBundle.Th';
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getContents()->toArray(),
            'textAlignment' => $this->getTextAlignment(),
            'filter' => $this->getFilter(),
            'sort' => $this->getSort(),
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

    /**
     * Get the value of sort
     *
     * @return ?array
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * Set the value of sort
     *
     * @param ?array $sort
     *
     * @return self
     */
    public function setSort(?array $sort): self
    {
        $this->sort = $sort;

        return $this;
    }
}
