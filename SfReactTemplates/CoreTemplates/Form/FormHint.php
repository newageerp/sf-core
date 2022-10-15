<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FormHint extends Template
{
    protected string $text = '';

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getProps(): array
    {
        return [
            'text' => $this->getText(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'form.hint';
    }

    /**
     * Get the value of text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @param string $text
     *
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
