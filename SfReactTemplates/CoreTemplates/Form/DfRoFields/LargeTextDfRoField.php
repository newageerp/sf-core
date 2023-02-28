<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormDfBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class LargeTextDfRoField extends FormDfBaseField
{
    public function getTemplateName(): string
    {
        return '_.AppBundle.LargeTextDfRoField';
    }
}
