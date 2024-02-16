<?php

namespace Newageerp\SfEntities\Event;

use Symfony\Contracts\EventDispatcher\Event;

class InitEntitiesEvent extends Event
{

    public const NAME = 'SfEntities.InitEntitiesEvent';

    protected array $entities = [];

    public function __construct(array $entities) {
        $this->entities = $entities;
    }

    /**
     * Get the value of entities
     *
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * Set the value of entities
     *
     * @param array $entities
     *
     * @return self
     */
    public function setEntities(array $entities): self
    {
        $this->entities = $entities;

        return $this;
    }
}
