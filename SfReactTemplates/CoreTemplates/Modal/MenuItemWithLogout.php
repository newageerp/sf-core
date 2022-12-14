<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItemWithLogout extends MenuItem
{
    public function __construct(
        string $children,
    ) {
        parent::__construct($children, null);
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.ModalBundle.MenuItemLogout';
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
