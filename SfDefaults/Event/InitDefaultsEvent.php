<?php

namespace Newageerp\SfDefaults\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InitDefaultsEvent extends Event
{

    public const NAME = 'SfDefaults.InitDefaultsEvent';

    protected array $defaults = [];

    public function __construct(array $defaults) {
        $this->defaults = $defaults;
    }

    /**
     * Get the value of defaults
     *
     * @return array
     */
    public function getDefaults(): array
    {
        return $this->defaults;
    }

    /**
     * Set the value of defaults
     *
     * @param array $defaults
     *
     * @return self
     */
    public function setDefaults(array $defaults): self
    {
        $this->defaults = $defaults;

        return $this;
    }
}
