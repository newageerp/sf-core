<?php

namespace Newageerp\SfReactTemplates\Template;

abstract class Template
{
    abstract public function getTemplateName(): string;

    abstract public function getProps(): array;

    public function getTemplateData(): array
    {
        return [];
    }

    public function getAction(): ?string
    {
        return null;
    }

    public function toArray(): array
    {
        return [
            'comp' => $this->getTemplateName(),
            'props' => $this->getProps(),
            'action' => $this->getAction(),
        ];
    }
}
