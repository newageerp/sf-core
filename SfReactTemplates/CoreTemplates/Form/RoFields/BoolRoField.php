<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\RoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class BoolRoField extends FormBaseField
{
    public function getTemplateName(): string
    {
        return '_.AppBundle.BoolRoField';
    }
}
