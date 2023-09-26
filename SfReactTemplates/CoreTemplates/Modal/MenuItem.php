<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItem extends Template
{
    protected string $children = '';
    protected ?string $iconName = null;
    protected ?bool $confirmation = null;

    protected bool $isActive = false;

    protected bool $isDisabled = false;

    public function __construct(string $children, ?string $iconName = null)
    {
        $this->children = $children;
        $this->iconName = $iconName;
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getChildren(),
            'iconName' => $this->getIconName(),
            'confirmation' => $this->getConfirmation(),
            'isActive' => $this->getIsActive(),
            'isDisabled' => $this->getIsDisabled(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.ModalBundle.MenuItem';
    }


    /**
     * Get the value of children
     *
     * @return string
     */
    public function getChildren(): string
    {
        return $this->children;
    }

    /**
     * Set the value of children
     *
     * @param string $children
     *
     * @return self
     */
    public function setChildren(string $children): self
    {
        $this->children = $children;

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
     * Get the value of confirmation
     *
     * @return ?bool
     */
    public function getConfirmation(): ?bool
    {
        return $this->confirmation;
    }

    /**
     * Set the value of confirmation
     *
     * @param ?bool $confirmation
     *
     * @return self
     */
    public function setConfirmation(?bool $confirmation): self
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    /**
     * Get the value of isActive
     *
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Set the value of isActive
     *
     * @param bool $isActive
     *
     * @return self
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get the value of isDisabled
     *
     * @return bool
     */
    public function getIsDisabled(): bool
    {
        return $this->isDisabled;
    }

    /**
     * Set the value of isDisabled
     *
     * @param bool $isDisabled
     *
     * @return self
     */
    public function setIsDisabled(bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }
}
