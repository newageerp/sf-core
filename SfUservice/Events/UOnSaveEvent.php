<?php

namespace Newageerp\SfUservice\Events;

use Newageerp\SfBaseEntity\Interface\IUser;
use Symfony\Contracts\EventDispatcher\Event;

class UOnSaveEvent extends Event
{
    public const NAME = 'sfuservice.saveevent';

    protected object $entity;

    public function __construct($entity    ) {
        $this->entity = $entity;
    }

    /**
     * Get the value of entity
     *
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * Set the value of entity
     *
     * @param object $entity
     *
     * @return self
     */
    public function setEntity(object $entity): self
    {
        $this->entity = $entity;

        return $this;
    }
}
