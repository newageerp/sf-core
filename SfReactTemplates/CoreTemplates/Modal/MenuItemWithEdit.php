<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItemWithEdit extends MenuItem
{
    protected int $elementId = 0;
    protected string $schema = '';
    protected string $type = '';
    protected ?bool $forcePopup = null;
    protected array $scopes = [];

    public function __construct(
        string $children,
        int $elementId,
        string $schema,
        string $type,
        ?bool $forcePopup = null
    ) {
        parent::__construct($children, null);

        $this->elementId = $elementId;
        $this->schema = $schema;
        $this->type = $type;
        $this->forcePopup = $forcePopup;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['elementId'] = $this->getElementId();
        $props['schema'] = $this->getSchema();
        $props['type'] = $this->getType();
        $props['forcePopup'] = $this->getForcePopup();
        $props['scopes'] = $this->getScopes();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'modal.menu-item-with-edit';
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
     * Get the value of forcePopup
     *
     * @return ?bool
     */
    public function getForcePopup(): ?bool
    {
        return $this->forcePopup;
    }

    /**
     * Set the value of forcePopup
     *
     * @param ?bool $forcePopup
     *
     * @return self
     */
    public function setForcePopup(?bool $forcePopup): self
    {
        $this->forcePopup = $forcePopup;

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

    /**
     * Get the value of schema
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @param string $schema
     *
     * @return self
     */
    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
