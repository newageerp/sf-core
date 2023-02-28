<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;

class HtmlEditorEditableField extends FormBaseField
{
    public function getProps(): array
    {
        $props = parent::getProps();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.HtmlEditorEditableField';
    }
}
