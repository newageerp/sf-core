<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\Template\Template;

class MainButton extends Template
{
    protected ?string $title = null;
    protected ?string $iconName = null;
    protected string $color = 'sky';
    protected ?string $className = null;
    protected ?bool $disabled = null;
    protected ?string $children = null;
    protected ?array $confirmation = null;

    public function getProps(): array
    {
        return [
            'title' => $this->getTitle(),
            'iconName' => $this->getIconName(),
            'color' => $this->getColor(),
            'className' => $this->getClassName(),
            'disabled' => $this->getDisabled(),
            'children' => $this->getChildren(),
            'confirmation' => $this->getConfirmation(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'buttons.main-button';
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
     * Get the value of iconName
     *
     * @return ?string
     */
    public function getIconName(): ?string
    {
        return $this->iconName;
    }

    /**
     * Set the value of iconName
     *
     * @param ?string $iconName
     *
     * @return self
     */
    public function setIconName(?string $iconName): self
    {
        $this->iconName = $iconName;

        return $this;
    }

    /**
     * Get the value of color
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @param string $color
     *
     * @return self
     */
    public function setColor(string $color): self
    {
        $this->color = $color;

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

    /**
     * Get the value of disabled
     *
     * @return ?bool
     */
    public function getDisabled(): ?bool
    {
        return $this->disabled;
    }

    /**
     * Set the value of disabled
     *
     * @param ?bool $disabled
     *
     * @return self
     */
    public function setDisabled(?bool $disabled): self
    {
        $this->disabled = $disabled;

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

    /**
     * Get the value of confirmation
     *
     * @return ?array
     */
    public function getConfirmation(): ?array
    {
        return $this->confirmation;
    }

    /**
     * Set the value of confirmation
     *
     * @param ?array $confirmation
     *
     * @return self
     */
    public function setConfirmation(?array $confirmation): self
    {
        $this->confirmation = $confirmation;

        return $this;
    }
}
