<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class CustomField extends FormBaseField
{
    protected string $componentName = '';

    public function __construct(string $key, string $componentName)
    {
        parent::__construct($key);

        $this->componentName = $componentName;
    }

    public function getProps(): array
    {
        $props = parent::getProps();


        return $props;
    }

    public function getTemplateName(): string
    {
        return 'customedit.' . $this->getComponentName();
    }


    /**
     * Get the value of componentName
     *
     * @return string
     */
    public function getComponentName(): string
    {
        return $this->componentName;
    }

    /**
     * Set the value of componentName
     *
     * @param string $componentName
     *
     * @return self
     */
    public function setComponentName(string $componentName): self
    {
        $this->componentName = $componentName;

        return $this;
    }
}
