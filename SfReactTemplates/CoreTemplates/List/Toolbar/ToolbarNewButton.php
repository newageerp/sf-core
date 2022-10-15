<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarNewButton extends Template
{
    protected string $schema;
    protected string $type;
    protected bool $forcePopup = false;

    public function __construct(string $schema, ?string $type = 'main')
    {
        $this->schema = $schema;
        $this->type = $type;
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar.new-button';
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'type' => $this->getType(),
            'forcePopup' => $this->getForcePopup(),
        ];
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

    /**
     * Get the value of forcePopup
     *
     * @return bool
     */
    public function getForcePopup(): bool
    {
        return $this->forcePopup;
    }

    /**
     * Set the value of forcePopup
     *
     * @param bool $forcePopup
     *
     * @return self
     */
    public function setForcePopup(bool $forcePopup): self
    {
        $this->forcePopup = $forcePopup;

        return $this;
    }
}
