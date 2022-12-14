<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItemWithLink extends MenuItem
{
    protected string $link = '';
    protected array $scopes = [];

    public function __construct(
        string $children,
        string $link,
    ) {
        parent::__construct($children, null);

        $this->link = $link;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['hrefNewWindow'] = $this->getLink();
        $props['scopes'] = $this->getScopes();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.ModalBundle.MenuItem';
    }


    /**
     * Get the value of scopes
     *
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Set the value of scopes
     *
     * @param array $scopes
     *
     * @return self
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Get the value of link
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Set the value of link
     *
     * @param string $link
     *
     * @return self
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
