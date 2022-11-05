<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Template;

class WhiteCardWithViewFormWidget extends Template
{
    protected string $viewId = '';
    protected ?string $title = '';
    protected ?string $editId = '';

    public function __construct(string $viewId, ?string $title, ?string $editId)
    {
        $this->viewId = $viewId;
        $this->title = $title;
        $this->editId = $editId;
    }

    public function getProps(): array
    {
        $props = parent::getProps();
        $props['viewId'] = $this->getViewId();
        $props['title'] = $this->getTitle();
        $props['editId']  = $this->getEditId();
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
}
