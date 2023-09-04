<?php

namespace Newageerp\SfUservice\Events;

use Newageerp\SfBaseEntity\Object\BaseUser;
use Symfony\Contracts\EventDispatcher\Event;

class UPermissionsEvent extends Event
{
    public const NAME = 'sfuservice.permissionsevent';

    protected BaseUser $user;

    protected array $filters = [];

    protected string $schema;

    public function __construct(BaseUser $user, array $filters, string $schema) {
        $this->user = $user;
        $this->schema = $schema;
        $this->filters = $filters;
    }

    /**
     * Get the value of user
     *
     * @return BaseUser
     */
    public function getUser(): BaseUser
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param BaseUser $user
     *
     * @return self
     */
    public function setUser(BaseUser $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of filters
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Set the value of filters
     *
     * @param array $filters
     *
     * @return self
     */
    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Get the value of schema
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @param string $schema
     *
     * @return self
     */
    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }
}
