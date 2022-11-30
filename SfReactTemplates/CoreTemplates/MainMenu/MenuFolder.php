<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfReactTemplates\Template\Template;

class MenuFolder extends Template
{
    protected ?string $iconName = null;

    protected string $title = '';

    /**
     * @var MenuItem[] $items
     */
    protected array $items = [];

    public function __construct(
        string $title,
        ?string $iconName = null
    ) {
        $this->title = $title;
        $this->iconName = $iconName;
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getTitle(),
            'iconName' => $this->getIconName(),
            'items' => array_map(
                function (MenuItem $item) {
                    return $item->toArray();
                },
                $this->getItems(),
            )
        ];
    }

    public function getTemplateName(): string
    {
        return 'main-menu.menu-folder';
    }

    /**
     * Get the value of iconName
     *
     * @return ?string
     */
    public function getIconName(): ?string
    {
        return $this->iconName;
    }

    /**
     * Set the value of iconName
     *
     * @param ?string $iconName
     *
     * @return self
     */
    public function setIconName(?string $iconName): self
    {
        $this->iconName = $iconName;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    public function addItem(MenuItem $item)
    {
        $this->items[] = $item;
    }
}
