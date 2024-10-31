<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Modal;

use Newageerp\SfReactTemplates\Template\Template;

class MenuItemWithAction extends MenuItem
{
    protected int $elementId = 0;
    protected string $url = '';
    protected array $scopes = [];

    public function __construct(
        string $children,
        int $elementId,
        string $url
    ) {
        parent::__construct($children, null);

        $this->elementId = $elementId;
        $this->url = $url;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['elementId'] = $this->getElementId();
        $props['url'] = $this->getUrl();
        $props['scopes'] = $this->getScopes();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.ModalBundle.MenuItemWithAction';
    }


    /**
     * Get the value of elementId
     *
     * @return int
     */
    public function getElementId(): int
    {
        return $this->elementId;
    }

    /**
     * Set the value of elementId
     *
     * @param int $elementId
     *
     * @return self
     */
    public function setElementId(int $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
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
