<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class RsButton extends Template
{
    protected string $schema = '';
    protected int $elementId = 0;
    protected Placeholder $children;
    protected string $defaultClick = 'main';

    public function __construct(string $schema, int $elementId)
    {
        $this->schema = $schema;
        $this->elementId = $elementId;
        $this->children = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'elementId' => $this->getElementId(),
            'children' => $this->getChildren()->toArray(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'buttons.rs-button';
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
     * Get the value of children
     *
     * @return Placeholder
     */
    public function getChildren(): Placeholder
    {
        return $this->children;
    }

    /**
     * Set the value of children
     *
     * @param Placeholder $children
     *
     * @return self
     */
    public function setChildren(Placeholder $children): self
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get the value of defaultClick
     *
     * @return string
     */
    public function getDefaultClick(): string
    {
        return $this->defaultClick;
    }

    /**
     * Set the value of defaultClick
     *
     * @param string $defaultClick
     *
     * @return self
     */
    public function setDefaultClick(string $defaultClick): self
    {
        $this->defaultClick = $defaultClick;

        return $this;
    }
}
