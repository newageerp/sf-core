<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class FormFieldTagCloud extends Template
{
    protected string $field = '';
    protected string $url = '';

    public function __construct(string $field, string $url)
    {
        $this->field = $field;
        $this->url = $url;
    }

    public function getProps(): array
    {
        return [
            'field' => $this->getField(),
            'url' => $this->getUrl(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.FormBundle.FormFieldTagCloudTemplate';
    }

    /**
     * Get the value of field
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Set the value of field
     *
     * @param string $field
     *
     * @return self
     */
    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Get the value of url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @param string $url
     *
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
