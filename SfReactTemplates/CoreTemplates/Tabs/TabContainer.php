<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Tabs;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class TabContainer extends Template
{
    protected ?string $title = '';

    /**
     * @var TabContainerItem[] $items
     */
    protected $items = [];

    public function __construct(?string $title = null)
    {
        $this->title = $title;
    }

    public function getProps(): array
    {
        $props = [
            'items' => array_map(
                function (TabContainerItem $item) {
                    return $item->getProps();
                },
                $this->items,
            ),
        ];
        if ($this->getTitle()) {
            $props['title'] = [
                'text' => $this->getTitle()
            ];
        }
        return $props;
    }

    public function addItem(TabContainerItem $item)
    {
        $this->items[] = $item;
    }

    public function getTemplateName(): string
    {
        return 'tabs.TabContainer';
    }

    /**
     * Get the value of items
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set the value of items
     */
    public function setItems($items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param ?string $title
     *
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
