<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Popup;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class PopupWindow extends Template
{
    protected ?Placeholder $children;

    protected ?string $size = null;

    public function __construct(?Placeholder $children = null)
    {
        $this->children = $children ? $children : new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getChildren()->toArray(),
            'size' => $this->getSize(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'popup.window';
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

    public function getTemplateData(): array
    {
        return $this->getChildren()->getTemplatesData();
    }

    /**
     * Get the value of size
     *
     * @return ?string
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @param ?string $size
     *
     * @return self
     */
    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }
}
