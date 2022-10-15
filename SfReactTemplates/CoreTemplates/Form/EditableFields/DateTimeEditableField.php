<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class DateTimeEditableField extends FormBaseField
{
    public function getProps(): array
    {
        $props = parent::getProps();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'form.editable.datetimefield';
    }
}
