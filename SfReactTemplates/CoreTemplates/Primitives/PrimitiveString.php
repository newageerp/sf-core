<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Primitives;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class PrimitiveString extends Template
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
        return 'primitives.string';
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
