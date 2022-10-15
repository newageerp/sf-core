<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Toolbar;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ToolbarActionButton extends Template
{
    protected string $title = "";

    protected string $iconName = "";

    protected Placeholder $afterClickContent;

    public function __construct(string $title, string $iconName)
    {
        $this->title = $title;
        $this->iconName = $iconName;
        $this->afterClickContent = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'iconName' => $this->getIconName(),
            'title' => $this->getTitle(),
            'afterClickContent' => $this->getAfterClickContent()->toArray(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'toolbar.action-button';
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
     * Get the value of iconName
     *
     * @return string
     */
    public function getIconName(): string
    {
        return $this->iconName;
    }

    /**
     * Set the value of iconName
     *
     * @param string $iconName
     *
     * @return self
     */
    public function setIconName(string $iconName): self
    {
        $this->iconName = $iconName;

        return $this;
    }

    /**
     * Get the value of afterClickContent
     *
     * @return Placeholder
     */
    public function getAfterClickContent(): Placeholder
    {
        return $this->afterClickContent;
    }

    /**
     * Set the value of afterClickContent
     *
     * @param Placeholder $afterClickContent
     *
     * @return self
     */
    public function setAfterClickContent(Placeholder $afterClickContent): self
    {
        $this->afterClickContent = $afterClickContent;

        return $this;
    }
}
