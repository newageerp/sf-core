<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItemWithAction extends MenuItem
{
    protected int $elementId = 0;
    protected string $action = '';
    protected array $scopes = [];

    public function __construct(
        string $children,
        int $elementId,
        string $action
    ) {
        parent::__construct($children, null);

        $this->elementId = $elementId;
        $this->action = $action;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['elementId'] = $this->getElementId();
        $props['action'] = $this->getAction();
        $props['scopes'] = $this->getScopes();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.ModalBundle.MenuItemWithAction';
    }


    /**
     * Get the value of elementId
     *
     * @return int
     */
    public function getElementId(): int
    {
        return $this->elementId;
    }

    /**
     * Set the value of elementId
     *
     * @param int $elementId
     *
     * @return self
     */
    public function setElementId(int $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
    }

    /**
     * Get the value of action
     *
     * @return string
     */
    public function getAction(): string
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

    /**
     * Get the value of scopes
     *
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Set the value of scopes
     *
     * @param array $scopes
     *
     * @return self
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }
}
