<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class StatusEditableField extends FormBaseField
{
    protected array $options = [];
    
    public function getProps(): array
    {
        $props = parent::getProps();

        $props['options'] = $this->getOptions();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.StatusEditableField';
    }

    /**
     * Get the value of options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
