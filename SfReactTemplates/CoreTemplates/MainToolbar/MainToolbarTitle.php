<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\MainToolbar;

use Newageerp\SfReactTemplates\Template\Template;

class MainToolbarTitle extends Template
{
    protected string $title = '';

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function getProps(): array
    {
        return [
            'title' => $this->getTitle(),
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTemplateName(): string
    {
        return 'maintoolbar.title';
    }
}
