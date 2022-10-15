<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;

class StringEditableField extends FormBaseField
{
    public function getProps(): array
    {
        $props = parent::getProps();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'form.editable.stringfield';
    }
}
