<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class RsButton extends Template
{
    protected string $schema = '';
    protected $elementId = 0;
    protected Placeholder $children;
    protected string $defaultClick = 'main';

    public function __construct(string $schema, $elementId)
    {
        $this->schema = $schema;
        $this->elementId = $elementId;
        $this->children = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'id' => $this->getElementId(),
            'button' => [
                'children' => $this->getChildren()->toArray(),
                'color' => 'white',
                'skipPadding' => true,
            ],
            'defaultClick' => $this->getDefaultClick(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.ButtonsBundle.RsButton';
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

    public function getElementId()
    {
        return $this->elementId;
    }

    public function setElementId($elementId): self
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
