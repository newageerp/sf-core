<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FormFieldSeparator extends Template
{
    public function getProps(): array
    {
        return [];
    }

    public function getTemplateName(): string
    {
        return 'form.fieldseparator';
    }
}
