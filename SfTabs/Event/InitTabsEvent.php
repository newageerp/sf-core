<?php

namespace Newageerp\SfTabs\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InitTabsEvent extends Event
{

    public const NAME = 'SfTabs.InitTabsEvent';

    protected array $tabs = [];

    public function __construct(array $tabs) {
        $this->tabs = $tabs;
    }


    /**
     * Get the value of tabs
     *
     * @return array
     */
    public function getTabs(): array
    {
        return $this->tabs;
    }

    /**
     * Set the value of tabs
     *
     * @param array $tabs
     *
     * @return self
     */
    public function setTabs(array $tabs): self
    {
        $this->tabs = $tabs;

        return $this;
    }
}
