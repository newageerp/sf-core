<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItem extends Template
{
    protected string $children = '';
    protected ?string $iconName = null;
    protected ?bool $confirmation = null;

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
        ];
    }

    public function getTemplateName(): string
    {
        return 'modal.menu-item';
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
}
