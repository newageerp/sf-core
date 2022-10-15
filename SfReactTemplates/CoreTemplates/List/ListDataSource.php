<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListDataSource extends Template
{
    protected Placeholder $children;
    protected ListToolbar $toolbar;

    protected bool $hidePaging = false;
    protected array $sort = [];

    protected string $schema = '';
    protected string $type = '';

    protected array $extraFilters = [];

    protected bool $scrollToHeaderOnLoad = true;

    protected ?array $socketData = null;

    protected int $pageSize = 20;

    public function __construct(string $schema, string $type)
    {
        $this->schema = $schema;
        $this->type = $type;
        $this->children = new Placeholder();
        $this->toolbar = new ListToolbar();
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'type' => $this->getType(),

            'socketData' => $this->getSocketData(),

            'children' => $this->getChildren()->toArray(),
            'hidePaging' => $this->getHidePaging(),
            'sort' => $this->getSort(),
            'extraFilters' => $this->getExtraFilters(),
            'scrollToHeaderOnLoad' => $this->getScrollToHeaderOnLoad(),

            'pageSize' => $this->getPageSize(),

            'toolbar' => [$this->getToolbar()->toArray()],
        ];
    }

    public function getTemplateName(): string
    {
        return 'list.list-data-source';
    }

    /**
     * Get the value of children
     *
     * @return Placeholder
     */
    public function getChildren(): Placeholder
    {
        return $this->children;
    }

    /**
     * Set the value of children
     *
     * @param Placeholder $children
     *
     * @return self
     */
    public function setChildren(Placeholder $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get the value of hidePaging
     *
     * @return bool
     */
    public function getHidePaging(): bool
    {
        return $this->hidePaging;
    }

    /**
     * Set the value of hidePaging
     *
     * @param bool $hidePaging
     *
     * @return self
     */
    public function setHidePaging(bool $hidePaging): self
    {
        $this->hidePaging = $hidePaging;

        return $this;
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

    /**
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of extraFilters
     *
     * @return array
     */
    public function getExtraFilters(): array
    {
        return $this->extraFilters;
    }

    /**
     * Set the value of extraFilters
     *
     * @param array $extraFilters
     *
     * @return self
     */
    public function setExtraFilters(array $extraFilters): self
    {
        $this->extraFilters = $extraFilters;

        return $this;
    }

    /**
     * Get the value of scrollToHeaderOnLoad
     *
     * @return bool
     */
    public function getScrollToHeaderOnLoad(): bool
    {
        return $this->scrollToHeaderOnLoad;
    }

    /**
     * Set the value of scrollToHeaderOnLoad
     *
     * @param bool $scrollToHeaderOnLoad
     *
     * @return self
     */
    public function setScrollToHeaderOnLoad(bool $scrollToHeaderOnLoad): self
    {
        $this->scrollToHeaderOnLoad = $scrollToHeaderOnLoad;

        return $this;
    }

    /**
     * Get the value of socketData
     *
     * @return ?array
     */
    public function getSocketData(): ?array
    {
        return $this->socketData;
    }

    /**
     * Set the value of socketData
     *
     * @param ?array $socketData
     *
     * @return self
     */
    public function setSocketData(?array $socketData): self
    {
        $this->socketData = $socketData;

        return $this;
    }

    /**
     * Get the value of pageSize
     *
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * Set the value of pageSize
     *
     * @param int $pageSize
     *
     * @return self
     */
    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * Get the value of toolbar
     *
     * @return ListToolbar
     */
    public function getToolbar(): ListToolbar
    {
        return $this->toolbar;
    }

    /**
     * Set the value of toolbar
     *
     * @param ListToolbar $toolbar
     *
     * @return self
     */
    public function setToolbar(ListToolbar $toolbar): self
    {
        $this->toolbar = $toolbar;

        return $this;
    }
}
