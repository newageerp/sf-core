<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Data;

use Newageerp\SfReactTemplates\Template\Template;

class DataString extends Template
{
    protected string $contents = '';

    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    public function getProps(): array
    {
        return [
            'contents' => $this->getContents(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'data.string';
    }

    /**
     * Get the value of contents
     *
     * @return string
     */
    public function getContents(): string
    {
        return $this->contents;
    }

    /**
     * Set the value of contents
     *
     * @param string $contents
     *
     * @return self
     */
    public function setContents(string $contents): self
    {
        $this->contents = $contents;

        return $this;
    }
}
