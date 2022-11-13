<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainMenu;

use Newageerp\SfReactTemplates\Template\Template;

class MenuTitle extends Template
{
    protected string $title = '';

    public function __construct(
        string $title,
    ) {
        $this->title = $title;
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getTitle()
        ];
    }

    public function getTemplateName(): string
    {
        return 'main-menu.menu-title';
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

}
