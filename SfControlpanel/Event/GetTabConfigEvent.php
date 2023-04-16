<?php

namespace Newageerp\SfControlpanel\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class GetTabConfigEvent extends Event
{
    public const NAME = 'sfcontrolpanel.GetTabConfigEvent';

    protected array $tab;

    public function __construct(array $tab,) {
        $this->tab = $tab;
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
