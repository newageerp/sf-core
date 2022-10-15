<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\CoreTemplates\Modal\Menu;
use Newageerp\SfReactTemplates\Template\Template;

class ToolbarButtonWithMenu extends Template
{
    protected ToolbarButton $button;
    protected Menu $menu;

    public function __construct(ToolbarButton $button, Menu $menu)
    {
        $this->button = $button;
        $this->menu = $menu;
    }

    public function getProps(): array
    {
        return [
            'button' => $this->getButton()->toArray()['props'],
            'menu' => $this->getMenu()->toArray()['props'],
        ];
    }

    public function getTemplateName(): string
    {
        return 'buttons.toolbar-button-with-menu';
    }


    /**
     * Get the value of button
     *
     * @return ToolbarButton
     */
    public function getButton(): ToolbarButton
    {
        return $this->button;
    }

    /**
     * Set the value of button
     *
     * @param ToolbarButton $button
     *
     * @return self
     */
    public function setButton(ToolbarButton $button): self
    {
        $this->button = $button;

        return $this;
    }

    /**
     * Get the value of menu
     *
     * @return Menu
     */
    public function getMenu(): Menu
    {
        return $this->menu;
    }

    /**
     * Set the value of menu
     *
     * @param Menu $menu
     *
     * @return self
     */
    public function setMenu(Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
