<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarQs extends Template
{
    protected array $fields;

    protected bool $instantSearch = false;
    
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.ListToolbarQs';
    }

    public function getProps(): array
    {
        return [
            'fields' => $this->getFields(),
            'instantSearch' => $this->getInstantSearch(),
        ];
    }


    /**
     * Get the value of fields
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the value of fields
     *
     * @param array $fields
     *
     * @return self
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Get the value of instantSearch
     *
     * @return bool
     */
    public function getInstantSearch(): bool
    {
        return $this->instantSearch;
    }

    /**
     * Set the value of instantSearch
     *
     * @param bool $instantSearch
     *
     * @return self
     */
    public function setInstantSearch(bool $instantSearch): self
    {
        $this->instantSearch = $instantSearch;

        return $this;
    }
}
