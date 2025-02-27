<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfReactTemplates\CoreTemplates\Layout\FlexRow;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListDataSource extends Template
{
    protected Placeholder $children;
    protected ListToolbar $toolbar;
    protected FlexRow $toolbarLine2;

    protected bool $hidePaging = false;
    protected array $sort = [];

    protected string $schema = '';
    protected string $type = '';

    protected array $extraFilters = [];

    protected bool $scrollToHeaderOnLoad = false;

    protected ?array $socketData = null;

    protected int $pageSize = 20;

    protected bool $hidePageSelectionSelect = false;

    protected bool $hideWithoutFilter = false;

    protected bool $compact = false;

    public function __construct(string $schema, string $type)
    {
        $this->schema = $schema;
        $this->type = $type;
        $this->children = new Placeholder();
        $this->toolbar = new ListToolbar($schema);

        $this->toolbarLine2 = new FlexRow();
        $this->toolbarLine2->setClassName('flex-wrap gap-4');
    }

    public function getProps(): array
    {
        $toolbar = [];
        if ($this->getToolbar()->hasTemplates()) {
            $toolbar[] = $this->getToolbar()->toArray();
        }
        $toolbarLine2 = [];
        if (count($this->getToolbarLine2()->getChildren()->getTemplates()) > 0) {
            $toolbarLine2[] = $this->getToolbarLine2()->toArray();
        }

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

            'toolbar' => $toolbar,
            'toolbarLine2' => $toolbarLine2,

            'hidePageSelectionSelect' => $this->getHidePageSelectionSelect(),

            'compact' => $this->getCompact(),
            'hideWithoutFilter' => $this->getHideWithoutFilter(),
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

    /**
     * Get the value of hidePageSelectionSelect
     *
     * @return bool
     */
    public function getHidePageSelectionSelect(): bool
    {
        return $this->hidePageSelectionSelect;
    }

    /**
     * Set the value of hidePageSelectionSelect
     *
     * @param bool $hidePageSelectionSelect
     *
     * @return self
     */
    public function setHidePageSelectionSelect(bool $hidePageSelectionSelect): self
    {
        $this->hidePageSelectionSelect = $hidePageSelectionSelect;

        return $this;
    }

    /**
     * Get the value of toolbarLine2
     *
     * @return FlexRow
     */
    public function getToolbarLine2(): FlexRow
    {
        return $this->toolbarLine2;
    }

    /**
     * Set the value of toolbarLine2
     *
     * @param FlexRow $toolbarLine2
     *
     * @return self
     */
    public function setToolbarLine2(FlexRow $toolbarLine2): self
    {
        $this->toolbarLine2 = $toolbarLine2;

        return $this;
    }

    /**
     * Get the value of compact
     *
     * @return bool
     */
    public function getCompact(): bool
    {
        return $this->compact;
    }

    /**
     * Set the value of compact
     *
     * @param bool $compact
     *
     * @return self
     */
    public function setCompact(bool $compact): self
    {
        $this->compact = $compact;

        return $this;
    }

    /**
     * Get the value of hideWithoutFilter
     *
     * @return bool
     */
    public function getHideWithoutFilter(): bool
    {
        return $this->hideWithoutFilter;
    }

    /**
     * Set the value of hideWithoutFilter
     *
     * @param bool $hideWithoutFilter
     *
     * @return self
     */
    public function setHideWithoutFilter(bool $hideWithoutFilter): self
    {
        $this->hideWithoutFilter = $hideWithoutFilter;

        return $this;
    }
}
