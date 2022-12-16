<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainToolbar;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class MainToolbarButtonsPlace extends Template
{
    protected string $className = '';

    protected Placeholder $children;

    public function __construct()
    {
        $this->children = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'className' => $this->getClassName(),
            'children' => $this->getChildren()->toArray(),
        ];
    }


    public function getTemplateName(): string
    {
        return '_.LayoutBundle.ButtonsPlace';
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
}
