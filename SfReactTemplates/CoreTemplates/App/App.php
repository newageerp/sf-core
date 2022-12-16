<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\App;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class App extends Template
{
    protected Placeholder $userSpaceWrapperLeft;
    protected Placeholder $userSpaceWrapperToolbarMenu;

    public function __construct()
    {
        $this->userSpaceWrapperLeft = new Placeholder();
        $this->userSpaceWrapperToolbarMenu = new Placeholder();
        $this->userSpaceWrapperToolbarButtons = new Placeholder();
    }

    public function getTemplateName(): string
    {
        return 'App';
    }

    public function getProps(): array
    {
        return [];
    }

    public function getTemplateData(): array
    {
        return [
            'userSpaceWrapperLeft' => $this->userSpaceWrapperLeft->toArray(),
            'userSpaceWrapperToolbarMenu' => $this->userSpaceWrapperToolbarMenu->toArray(),
        ];
    }

    /**
     * Get the value of userSpaceWrapperLeft
     *
     * @return Placeholder
     */
    public function getUserSpaceWrapperLeft(): Placeholder
    {
        return $this->userSpaceWrapperLeft;
    }

    /**
     * Set the value of userSpaceWrapperLeft
     *
     * @param Placeholder $userSpaceWrapperLeft
     *
     * @return self
     */
    public function setUserSpaceWrapperLeft(Placeholder $userSpaceWrapperLeft): self
    {
        $this->userSpaceWrapperLeft = $userSpaceWrapperLeft;

        return $this;
    }

    /**
     * Get the value of userSpaceWrapperToolbarMenu
     *
     * @return Placeholder
     */
    public function getUserSpaceWrapperToolbarMenu(): Placeholder
    {
        return $this->userSpaceWrapperToolbarMenu;
    }

    /**
     * Set the value of userSpaceWrapperToolbarMenu
     *
     * @param Placeholder $userSpaceWrapperToolbarMenu
     *
     * @return self
     */
    public function setUserSpaceWrapperToolbarMenu(Placeholder $userSpaceWrapperToolbarMenu): self
    {
        $this->userSpaceWrapperToolbarMenu = $userSpaceWrapperToolbarMenu;

        return $this;
    }
}
