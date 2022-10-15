<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class EnumNumberColumn extends ListBaseColumn {
    protected array $options = [];

    public function __construct(string $key, array $options)
    {
        parent::__construct($key);
        $this->options = $options;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['options'] = $this->getOptions();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'list.ro.enumnumbercolumn';
    }

    /**
     * Get the value of options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }
}