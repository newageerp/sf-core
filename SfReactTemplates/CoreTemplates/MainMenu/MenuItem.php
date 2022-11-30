<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItem extends Template
{
    protected ?string $iconName = null;

    protected string $title = '';

    protected string $link = '';

    public function __construct(
        string $title,
        string $link,
        ?string $iconName = null
    ) {
        $this->title = $title;
        $this->link = $link;
        $this->iconName = $iconName;
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getTitle(),
            'iconName' => $this->getIconName(),
            'href' => $this->getLink(),
            'type' => 'menu-item'
        ];
    }

    public function getTemplateName(): string
    {
        return 'main-menu.menu-item';
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
     * Get the value of link
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Set the value of link
     *
     * @param string $link
     *
     * @return self
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
