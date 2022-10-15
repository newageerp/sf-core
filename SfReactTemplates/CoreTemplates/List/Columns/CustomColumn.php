<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class CustomColumn extends ListBaseColumn {
    protected string $componentName = '';

    public function __construct(string $key, string $componentName)
    {
        parent::__construct($key);

        $this->componentName = $componentName;
    }

    public function getProps(): array
    {
        $props = parent::getProps();


        return $props;
    }

    public function getTemplateName(): string
    {
        return 'customlist.' . $this->getComponentName();
    }


    /**
     * Get the value of componentName
     *
     * @return string
     */
    public function getComponentName(): string
    {
        return $this->componentName;
    }

    /**
     * Set the value of componentName
     *
     * @param string $componentName
     *
     * @return self
     */
    public function setComponentName(string $componentName): self
    {
        $this->componentName = $componentName;

        return $this;
    }
}