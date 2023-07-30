<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View\FilesWidget;

use Newageerp\SfReactTemplates\Template\Template;

class FilesWidget extends Template
{
    protected bool $isCompact = false;
    /**
     * @var FilesWidgetItem[] $items
     */
    protected array $items = [];

    public function getProps(): array
    {
        return [
            'isCompact' => $this->getIsCompact(),
            'items' => array_map(
                function (FilesWidgetItem $item) {
                    return $item->toArray();
                },
                $this->getItems()
            )
        ];
    }

    public function getTemplateName(): string
    {
        return '_.WidgetsBundle.MultipleFilesWidget';
    }

    /**
     * Get the value of items
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @param array $items
     *
     * @return self
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @var FilesWidgetItem $item
     */
    public function addItem(FilesWidgetItem $item)
    {
        $this->items[] = $item;
    }

    /**
     * Get the value of isCompact
     *
     * @return bool
     */
    public function getIsCompact(): bool
    {
        return $this->isCompact;
    }

    /**
     * Set the value of isCompact
     *
     * @param bool $isCompact
     *
     * @return self
     */
    public function setIsCompact(bool $isCompact): self
    {
        $this->isCompact = $isCompact;

        return $this;
    }
}
