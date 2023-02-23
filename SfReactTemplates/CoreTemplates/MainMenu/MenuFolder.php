<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfReactTemplates\Template\Template;

class MenuFolder extends Template
{
    protected ?string $iconName = null;

    protected string $title = '';

    protected string $className = '';

    protected string $contentClassName = '';

    protected string $menuFolderId = '';

    /**
     * @var array $items
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
            'className' => $this->getClassName(),
            'contentClassName' => $this->getContentClassName(),
            'children' => $this->getTitle(),
            'iconName' => $this->getIconName(),
            'menuFolderId' => $this->getMenuFolderId(),
            'items' => array_map(
                function ($item) {
                    return $item->getProps();
                },
                $this->getItems(),
            ),
            'type' => 'menu-folder'
        ];
    }

    public function getTemplateName(): string
    {
        return '_.MenuBundle.MenuFolder';
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

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    /**
     * Get the value of menuFolderId
     *
     * @return string
     */
    public function getMenuFolderId(): string
    {
        return $this->menuFolderId;
    }

    /**
     * Set the value of menuFolderId
     *
     * @param string $menuFolderId
     *
     * @return self
     */
    public function setMenuFolderId(string $menuFolderId): self
    {
        $this->menuFolderId = $menuFolderId;

        return $this;
    }

    /**
     * Get the value of className
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Set the value of className
     *
     * @param string $className
     *
     * @return self
     */
    public function setClassName(string $className): self
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get the value of contentClassName
     *
     * @return string
     */
    public function getContentClassName(): string
    {
        return $this->contentClassName;
    }

    /**
     * Set the value of contentClassName
     *
     * @param string $contentClassName
     *
     * @return self
     */
    public function setContentClassName(string $contentClassName): self
    {
        $this->contentClassName = $contentClassName;

        return $this;
    }
}
