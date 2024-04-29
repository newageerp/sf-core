<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Buttons;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FindButton extends Template
{
    protected string $entity = '';
    protected string $field = '';
    protected string $value = '';

    protected Placeholder $children;


    public function __construct(string $entity, string $field, string $value)
    {
        $this->entity = $entity;
        $this->field = $field;
        $this->value = $value;

        $this->children = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'entity' => $this->getEntity(),
            'field' => $this->getField(),
            'value' => $this->getValue(),
            'children' => $this->getChildren()->toArray(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.ButtonsBundle.FindButton';
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
     * Get the value of entity
     *
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * Set the value of entity
     *
     * @param string $entity
     *
     * @return self
     */
    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get the value of field
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Set the value of field
     *
     * @param string $field
     *
     * @return self
     */
    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get the value of value
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @param string $value
     *
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
