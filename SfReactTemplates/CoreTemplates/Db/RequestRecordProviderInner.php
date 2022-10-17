<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Db;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class RequestRecordProviderInner extends Template
{
    protected Placeholder $children;

    public function __construct()
    {
        $this->children = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getChildren()->toArray(),
        ];
    }


    public function getTemplateName(): string
    {
        return 'db.request.recordprovider.inner';
    }

    /**
     * Get the value of children
     *
     * @return Placeholder
     */
    public function getChildren(): Placeholder
    {
        return $this->children;
    }

    /**
     * Set the value of children
     *
     * @param Placeholder $children
     *
     * @return self
     */
    public function setChildren(Placeholder $children): self
    {
        $this->children = $children;

        return $this;
    }
}