<?php

namespace Newageerp\SfProperties\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InitPropertiesEvent extends Event
{
    public const NAME = 'SfProperties.InitPropertiesEvent';

    protected array $properties = [];

    public function __construct(array $properties) {
        $this->properties = $properties;
    }


    /**
     * Get the value of properties
     *
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * Set the value of properties
     *
     * @param array $properties
     *
     * @return self
     */
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }
}
