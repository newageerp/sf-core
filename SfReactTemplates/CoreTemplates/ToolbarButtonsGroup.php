<?php

namespace Newageerp\SfReactTemplates\CoreTemplates;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ToolbarButtonsGroup extends Template
{
    protected Placeholder $children;

    public function __construct(Placeholder $children)
    {
        $this->children = $children;
    }
    
    public function getTemplateName(): string
    {
        return 'sf.toolbar-buttons-group';
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getChildren()->toArray(),
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
}
