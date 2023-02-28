<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FileRoField extends FormBaseField
{
    protected bool $short = false;

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['short'] = $this->getShort();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.FileRoField';
    }

    /**
     * Get the value of short
     *
     * @return bool
     */
    public function getShort(): bool
    {
        return $this->short;
    }

    /**
     * Set the value of short
     *
     * @param bool $short
     *
     * @return self
     */
    public function setShort(bool $short): self
    {
        $this->short = $short;

        return $this;
    }
}
