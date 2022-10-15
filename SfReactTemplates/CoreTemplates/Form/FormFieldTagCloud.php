<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FormFieldTagCloud extends Template
{
    protected string $field = '';
    protected string $action = '';

    public function __construct(string $field, string $action)
    {
        $this->field = $field;
        $this->action = $action;
    }

    public function getProps(): array
    {
        return [
            'field' => $this->getField(),
            'action' => $this->getAction(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'form.fieldtagcloud';
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
}
