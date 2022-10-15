<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormDfBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class EnumTextDfRoField extends FormDfBaseField
{
    protected array $options = [];

    public function __construct(string $key, int $id, array $options)
    {
        parent::__construct($key, $id);
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
        return 'form.dfro.enumtextfield';
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
