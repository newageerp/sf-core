<?php

namespace Newageerp\SfReactTemplates\CoreTemplates;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarButton extends Template
{
    protected string $title = "";
    
    protected string $iconName = "";

    protected bool $disabled = false;

    protected bool $confirmation = false;

    protected string $className = "";

    protected string $action = "";

    public function getProps(): array
    {
        return [
            'iconName' => $this->getIconName(),
            'title' => $this->getTitle(),
            'disabled' => $this->getDisabled(),
            'confirmation' => $this->getConfirmation(),
            'className' => $this->getClassName(),
            'action' => $this->getAction(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'sf.toolbar-button';
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
     * Get the value of disabled
     *
     * @return bool
     */
    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Set the value of disabled
     *
     * @param bool $disabled
     *
     * @return self
     */
    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * Get the value of confirmation
     *
     * @return bool
     */
    public function getConfirmation(): bool
    {
        return $this->confirmation;
    }

    /**
     * Set the value of confirmation
     *
     * @param bool $confirmation
     *
     * @return self
     */
    public function setConfirmation(bool $confirmation): self
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    /**
     * Get the value of className
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Set the value of className
     *
     * @param string $className
     *
     * @return self
     */
    public function setClassName(string $className): self
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get the value of action
     *
     * @return string
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Set the value of action
     *
     * @param string $action
     *
     * @return self
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }
}
