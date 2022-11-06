<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Tabs;

use Newageerp\SfReactTemplates\Template\Template;
use Newageerp\SfReactTemplates\Template\Placeholder;

class TabContainerItem extends Template
{
    protected Placeholder $content;
    protected string $title = '';

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->content = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'title' => $this->getTitle(),
            'content' => $this->getContent()->toArray(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'tabs.TabContainerItem';
    }

    /**
     * Get the value of content
     *
     * @return Placeholder
     */
    public function getContent(): Placeholder
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param Placeholder $content
     *
     * @return self
     */
    public function setContent(Placeholder $content): self
    {
        $this->content = $content;

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
