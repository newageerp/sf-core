<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class RoForm extends Template
{
    protected Placeholder $children;

    protected bool $isCompact = false;

    public function __construct(?Placeholder $children = null, bool $isCompact = false)
    {
        $this->children = $children ? $children : new Placeholder();
        $this->isCompact = $isCompact;
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getChildren()->toArray(),
            'isCompact' => $this->getIsCompact(),
        ];
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

    public function getTemplateName(): string
    {
        return 'form.roform';
    }

    /**
     * Get the value of isCompact
     *
     * @return bool
     */
    public function getIsCompact(): bool
    {
        return $this->isCompact;
    }

    /**
     * Set the value of isCompact
     *
     * @param bool $isCompact
     *
     * @return self
     */
    public function setIsCompact(bool $isCompact): self
    {
        $this->isCompact = $isCompact;

        return $this;
    }
}
