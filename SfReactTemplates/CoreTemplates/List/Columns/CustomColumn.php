<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class CustomColumn extends ListBaseColumn {
    protected string $componentName = '';

    protected string $schema = '';

    public function __construct(string $key, string $schema, string $componentName)
    {
        parent::__construct($key);

        $this->schema = $schema;
        $this->componentName = $componentName;
    }

    public function getProps(): array
    {
        $props = [
            'templateKey' => 'window.list.row',
            'position' => 'customField',
            'options' => [
                'dataSource' => $this->schema,
                'fieldKey' => $this->key,
                'name' => $this->componentName,
            ],
        ];


        return $props;
    }

    public function getTemplateName(): string
    {
        return 'ui.templates.resolver';
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

    /**
     * Get the value of schema
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @param string $schema
     *
     * @return self
     */
    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }
}