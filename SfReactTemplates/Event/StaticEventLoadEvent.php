<?php

namespace Newageerp\SfReactTemplates\Event;

use Symfony\Contracts\EventDispatcher\Event;

class StaticEventLoadEvent extends Event
{
    public const NAME = 'StaticEventLoadEvent';

    protected array $staticEvent = [];

    public function __construct(array $staticEvent) {
        $this->staticEvent = $staticEvent;
    }

    /**
     * Get the value of staticEvent
     *
     * @return $staticEvent
     */
    public static function getStaticEvent(): array
    {
        return self::$staticEvent;
    }

    /**
     * Set the value of staticEvent
     *
     * @param $staticEvent $staticEvent
     */
    public static function setStaticEvent(array $staticEvent)
    {
        self::$staticEvent = $staticEvent;
    }
}
