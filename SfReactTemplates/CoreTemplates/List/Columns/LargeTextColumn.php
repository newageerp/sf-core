<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class LargeTextColumn extends ListBaseColumn {
    protected int $initRows = 3;

    protected bool $initShowAll = false;

    protected string $expandIn = 'inline';

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['initRows'] = $this->getInitRows();
        $props['initShowAll'] = $this->getInitShowAll();
        $props['expandIn'] = $this->getExpandIn();

        return $props;
    }
    
    
    public function getTemplateName(): string
    {
        return '_.AppBundle.LargeTextRoColumn';
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

    /**
     * Get the value of expandIn
     *
     * @return string
     */
    public function getExpandIn(): string
    {
        return $this->expandIn;
    }

    /**
     * Set the value of expandIn
     *
     * @param string $expandIn
     *
     * @return self
     */
    public function setExpandIn(string $expandIn): self
    {
        $this->expandIn = $expandIn;

        return $this;
    }
}