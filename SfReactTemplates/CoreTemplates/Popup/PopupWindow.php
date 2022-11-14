<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Popup;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class PopupWindow extends Template
{
    protected ?Placeholder $children;

    protected ?string $size = null;

    protected string $title = '';

    public function __construct(?Placeholder $children = null)
    {
        $this->children = $children ? $children : new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getChildren()->toArray(),
            'size' => $this->getSize(),
            'title' => $this->getTitle(),
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
