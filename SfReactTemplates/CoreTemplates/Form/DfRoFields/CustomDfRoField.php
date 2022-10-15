<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormDfBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class CustomDfRoField extends FormDfBaseField
{
    protected string $componentName = '';

    public function __construct(string $key, int $id, string $componentName)
    {
        parent::__construct($key, $id);

        $this->componentName = $componentName;
    }

    public function getProps(): array
    {
        $props = parent::getProps();


        return $props;
    }

    public function getTemplateName(): string
    {
        return 'customviewdf.' . $this->getComponentName();
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
