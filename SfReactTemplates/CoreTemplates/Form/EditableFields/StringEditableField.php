<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;

class StringEditableField extends FormBaseField
{
    protected ?string $as = '';

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['as'] = $this->getAs();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.StringEditableField';
    }

    /**
     * Get the value of as
     *
     * @return ?string
     */
    public function getAs(): ?string
    {
        return $this->as;
    }

    /**
     * Set the value of as
     *
     * @param ?string $as
     *
     * @return self
     */
    public function setAs(?string $as): self
    {
        $this->as = $as;

        return $this;
    }
}
