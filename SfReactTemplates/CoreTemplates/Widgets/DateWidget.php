<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Template;

class DateWidget extends Template
{
    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $color = null;

    protected ?string $children = null;

    protected bool $isCompact = false;

    public function __construct()
    {
    }

    public function getProps(): array
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'children' => $this->getChildren(),
            'color' => $this->getColor(),
            'isCompact' => $this->getIsCompact(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.WidgetsBundle.DateCardWidget';
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
     * Get the value of description
     *
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param ?string $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of color
     *
     * @return ?string
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @param ?string $color
     *
     * @return self
     */
    public function setColor(?string $color): self
    {
        $this->color = $color;

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


    /**
     * Get the value of children
     *
     * @return ?string
     */
    public function getChildren(): ?string
    {
        return $this->children;
    }

    /**
     * Set the value of children
     *
     * @param ?string $children
     *
     * @return self
     */
    public function setChildren(?string $children): self
    {
        $this->children = $children;

        return $this;
    }
}
