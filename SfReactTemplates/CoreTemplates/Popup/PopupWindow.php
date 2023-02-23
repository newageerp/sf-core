<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Popup;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class PopupWindow extends Template
{
    protected ?Placeholder $children;

    protected ?string $className = null;

    protected string $title = '';

    public function __construct(?Placeholder $children = null)
    {
        $this->children = $children ? $children : new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getChildren()->toArray(),
            'className' => $this->getClassName(),
            'title' => $this->getTitle(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.PopupBundle.PopupWindow';
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

    /**
     * Get the value of className
     *
     * @return ?string
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * Set the value of className
     *
     * @param ?string $className
     *
     * @return self
     */
    public function setClassName(?string $className): self
    {
        $this->className = $className;

        return $this;
    }
}
