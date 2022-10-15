<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FormLabel extends Template
{
    protected string $text = '';

    protected ?string $paddingTop = null;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getProps(): array
    {
        return [
            'text' => $this->getText(),
            'paddingTop' => $this->getPaddingTop(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'form.label';
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

    /**
     * Get the value of paddingTop
     *
     * @return ?string
     */
    public function getPaddingTop(): ?string
    {
        return $this->paddingTop;
    }

    /**
     * Set the value of paddingTop
     *
     * @param ?string $paddingTop
     *
     * @return self
     */
    public function setPaddingTop(?string $paddingTop): self
    {
        $this->paddingTop = $paddingTop;

        return $this;
    }
}
