<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class UniColumn extends ListBaseColumn
{
    protected string $path = '';

    protected bool $editable = false;

    protected ?array $style = null;

    protected ?array $options = null;

    protected ?array $link = null;

    public function __construct(string $path, string $fieldKey)
    {
        parent::__construct($fieldKey);
        $this->path = $path;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['editable'] = $this->getEditable();
        $props['style'] = $this->getStyle();
        $props['link'] = $this->getLink();
        $props['path'] = $this->getPath();
        $props['options'] = $this->getOptions();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'bit-UniRoColumn';
    }

    /**
     * Get the value of style
     *
     * @return ?array
     */
    public function getStyle(): ?array
    {
        return $this->style;
    }

    /**
     * Set the value of style
     *
     * @param ?array $style
     *
     * @return self
     */
    public function setStyle(?array $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get the value of link
     *
     * @return ?array
     */
    public function getLink(): ?array
    {
        return $this->link;
    }

    /**
     * Set the value of link
     *
     * @param ?array $link
     *
     * @return self
     */
    public function setLink(?array $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get the value of path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get the value of options
     *
     * @return ?array
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @param ?array $options
     *
     * @return self
     */
    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function appendOptions(string $key, array $options) {
        if (!$this->options) $this->options = [];
        $this->options[$key] = $options;
    }

    /**
     * Get the value of editable
     *
     * @return bool
     */
    public function getEditable(): bool
    {
        return $this->editable;
    }

    /**
     * Set the value of editable
     *
     * @param bool $editable
     *
     * @return self
     */
    public function setEditable(bool $editable): self
    {
        $this->editable = $editable;

        return $this;
    }
}
