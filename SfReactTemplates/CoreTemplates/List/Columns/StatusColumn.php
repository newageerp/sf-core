<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class StatusColumn extends ListBaseColumn {
    protected string $schema = '';

    protected bool $isSmall = false;

    public function __construct(string $key, string $schema)
    {
        parent::__construct($key);
        $this->schema = $schema;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['schema'] = $this->getSchema();
        $props['isSmall'] = $this->getIsSmall();

        return $props;
    }
    
    public function getTemplateName(): string
    {
        return 'list.ro.statuscolumn';
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

    /**
     * Get the value of isSmall
     *
     * @return bool
     */
    public function getIsSmall(): bool
    {
        return $this->isSmall;
    }

    /**
     * Set the value of isSmall
     *
     * @param bool $isSmall
     *
     * @return self
     */
    public function setIsSmall(bool $isSmall): self
    {
        $this->isSmall = $isSmall;

        return $this;
    }
}