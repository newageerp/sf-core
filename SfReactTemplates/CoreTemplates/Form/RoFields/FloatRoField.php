<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FloatRoField extends FormBaseField
{
    protected int $accuracy = 2;

    public function __construct(string $key, int $accuracy = 2)
    {
        parent::__construct($key);
        $this->accuracy = $accuracy;

    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['accuracy'] = $this->getAccuracy();

        return $props;
    }
    
    public function getTemplateName(): string
    {
        return 'form.ro.floatfield';
    }

    /**
     * Get the value of accuracy
     *
     * @return int
     */
    public function getAccuracy(): int
    {
        return $this->accuracy;
    }

    /**
     * Set the value of accuracy
     *
     * @param int $accuracy
     *
     * @return self
     */
    public function setAccuracy(int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }
}
