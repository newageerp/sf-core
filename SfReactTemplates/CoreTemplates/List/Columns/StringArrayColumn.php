<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class StringArrayColumn extends ListBaseColumn {
    protected int $initRows = 3;

    protected bool $initShowAll = false;

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['initRows'] = $this->getInitRows();
        $props['initShowAll'] = $this->getInitShowAll();

        return $props;
    }
    

    public function getTemplateName(): string
    {
        return '_.AppBundle.StringArrayRoColumn';
    }

    /**
     * Get the value of initRows
     *
     * @return int
     */
    public function getInitRows(): int
    {
        return $this->initRows;
    }

    /**
     * Set the value of initRows
     *
     * @param int $initRows
     *
     * @return self
     */
    public function setInitRows(int $initRows): self
    {
        $this->initRows = $initRows;

        return $this;
    }

    /**
     * Get the value of initShowAll
     *
     * @return bool
     */
    public function getInitShowAll(): bool
    {
        return $this->initShowAll;
    }

    /**
     * Set the value of initShowAll
     *
     * @param bool $initShowAll
     *
     * @return self
     */
    public function setInitShowAll(bool $initShowAll): self
    {
        $this->initShowAll = $initShowAll;

        return $this;
    }
}