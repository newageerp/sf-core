<?php

namespace Newageerp\SfReactTemplates\Event;

use Symfony\Contracts\EventDispatcher\Event;

class StaticEventLoadEvent extends Event
{
    public const NAME = 'StaticEventLoadEvent';

    protected array $staticEvent;

    public function __construct(array $staticEvent) {
        $this->staticEvent = $staticEvent;
    }


    /**
     * Get the value of staticEvent
     *
     * @return array
     */
    public function getStaticEvent(): array
    {
        return $this->staticEvent;
    }

    /**
     * Set the value of staticEvent
     *
     * @param array $staticEvent
     *
     * @return self
     */
    public function setStaticEvent(array $staticEvent): self
    {
        $this->staticEvent = $staticEvent;

        return $this;
    }
}
