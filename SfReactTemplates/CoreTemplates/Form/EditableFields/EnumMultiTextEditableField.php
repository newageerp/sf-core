<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class EnumMultiTextEditableField extends FormBaseField
{
    protected array $optisons = [];

    public function __construct(string $key, array $options)
    {
        parent::__construct($key);
        $this->options = $options;
    }
    
    public function getProps(): array
    {
        $props = parent::getProps();

        $props['options'] = $this->getOptions();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'form.editable.enummultitextfield';
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
