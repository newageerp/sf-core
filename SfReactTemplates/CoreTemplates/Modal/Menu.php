<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class Menu extends Template
{
    protected Placeholder $children;

    protected bool $isAbsolute = true;

    public function __construct(bool $isAbsolute)
    {
        $this->isAbsolute = $isAbsolute;
        $this->children = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'isAbsolute' => $this->getIsAbsolute(),
            'children' => $this->getChildren()->toArray(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'modal.menu';
    }

    /**
     * Get the value of isAbsolute
     *
     * @return bool
     */
    public function getIsAbsolute(): bool
    {
        return $this->isAbsolute;
    }

    /**
     * Set the value of isAbsolute
     *
     * @param bool $isAbsolute
     *
     * @return self
     */
    public function setIsAbsolute(bool $isAbsolute): self
    {
        $this->isAbsolute = $isAbsolute;

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
