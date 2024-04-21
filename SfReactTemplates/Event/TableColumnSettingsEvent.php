<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class TableColumnSettingsEvent extends Event
{
    public const NAME = 'sfreacttemplates.TableColumnParseEvent';

    protected array $col = [];

    protected array $tab = [];

    public function __construct(
        array $col,
        array $tab,
    ) {
        $this->col = $col;
        $this->tab = $tab;
    }


    /**
     * Get the value of col
     *
     * @return array
     */
    public function getCol(): array
    {
        return $this->col;
    }

    /**
     * Set the value of col
     *
     * @param array $col
     *
     * @return self
     */
    public function setCol(array $col): self
    {
        $this->col = $col;

        return $this;
    }

    /**
     * Get the value of tab
     *
     * @return array
     */
    public function getTab(): array
    {
        return $this->tab;
    }

    /**
     * Set the value of tab
     *
     * @param array $tab
     *
     * @return self
     */
    public function setTab(array $tab): self
    {
        $this->tab = $tab;

        return $this;
    }
}
