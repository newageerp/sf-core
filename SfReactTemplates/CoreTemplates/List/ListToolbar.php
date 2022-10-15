<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfPermissions\Service\EntityPermissionService;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListToolbar extends Template
{
    protected Placeholder $toolbarLeft;

    protected Placeholder $toolbarRight;

    protected Placeholder $toolbarMiddle;

    public function __construct()
    {
        $this->toolbarLeft = new Placeholder();
        $this->toolbarRight = new Placeholder();
        $this->toolbarMiddle = new Placeholder();
    }
    

    public function getProps(): array
    {
        return [
            'toolbarLeft' => $this->getToolbarLeft()->toArray(),
            'toolbarRight' => $this->getToolbarRight()->toArray(),
            'toolbarMiddle' => $this->getToolbarMiddle()->toArray(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar';
    }

    /**
     * Get the value of toolbarLeft
     *
     * @return Placeholder
     */
    public function getToolbarLeft(): Placeholder
    {
        return $this->toolbarLeft;
    }

    /**
     * Set the value of toolbarLeft
     *
     * @param Placeholder $toolbarLeft
     *
     * @return self
     */
    public function setToolbarLeft(Placeholder $toolbarLeft): self
    {
        $this->toolbarLeft = $toolbarLeft;

        return $this;
    }

    /**
     * Get the value of toolbarRight
     *
     * @return Placeholder
     */
    public function getToolbarRight(): Placeholder
    {
        return $this->toolbarRight;
    }

    /**
     * Set the value of toolbarRight
     *
     * @param Placeholder $toolbarRight
     *
     * @return self
     */
    public function setToolbarRight(Placeholder $toolbarRight): self
    {
        $this->toolbarRight = $toolbarRight;

        return $this;
    }

    /**
     * Get the value of toolbarMiddle
     *
     * @return Placeholder
     */
    public function getToolbarMiddle(): Placeholder
    {
        return $this->toolbarMiddle;
    }

    /**
     * Set the value of toolbarMiddle
     *
     * @param Placeholder $toolbarMiddle
     *
     * @return self
     */
    public function setToolbarMiddle(Placeholder $toolbarMiddle): self
    {
        $this->toolbarMiddle = $toolbarMiddle;

        return $this;
    }
}
