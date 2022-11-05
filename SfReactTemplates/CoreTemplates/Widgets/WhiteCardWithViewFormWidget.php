<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class WhiteCardWithViewFormWidget extends Template
{
    protected ?string $title = '';
    protected ?string $editId = '';
    protected bool $isCompact = false;
    protected Placeholder $content;

    public function __construct(
        ?string $title,
        ?string $editId,
        ?bool $isCompact = false,
    ) {
        $this->title = $title;
        $this->editId = $editId;
        $this->isCompact = $isCompact;

        $this->content = new Placeholder();
    }

    public function getProps(): array
    {
        $props = [];
        $props['isCompact'] = $this->getIsCompact();
        $props['title'] = $this->getTitle();
        $props['editId']  = $this->getEditId();
        $props['content'] = $this->getContent()->toArray();
        return $props;
    }

    public function getTemplateName(): string
    {
        return 'widgets.WhiteCardWithViewFormWidget';
    }

    /**
     * Get the value of viewId
     *
     * @return string
     */
    public function getViewId(): string
    {
        return $this->viewId;
    }

    /**
     * Set the value of viewId
     *
     * @param string $viewId
     *
     * @return self
     */
    public function setViewId(string $viewId): self
    {
        $this->viewId = $viewId;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param ?string $title
     *
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of editId
     *
     * @return ?string
     */
    public function getEditId(): ?string
    {
        return $this->editId;
    }

    /**
     * Set the value of editId
     *
     * @param ?string $editId
     *
     * @return self
     */
    public function setEditId(?string $editId): self
    {
        $this->editId = $editId;

        return $this;
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
