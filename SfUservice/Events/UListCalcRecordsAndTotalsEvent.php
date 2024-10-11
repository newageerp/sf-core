<?php

namespace Newageerp\SfUservice\Events;

use Newageerp\SfBaseEntity\Interface\IUser;
use Symfony\Contracts\EventDispatcher\Event;

class UListCalcRecordsAndTotalsEvent extends Event
{
    public const NAME = 'sfuservice.UListCalcRecordsAndTotalsEvent';


    protected string $schema;

    protected ?IUser $user;

    protected bool $calcRecordsAndTotals;

    public function __construct(
        string $schema,
        bool $calcRecordsAndTotals,
        ?IUser $user
    ) {
        $this->schema = $schema;
        $this->calcRecordsAndTotals = $calcRecordsAndTotals;
        $this->user = $user;
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

    /**
     * Get the value of user
     *
     * @return ?IUser
     */
    public function getUser(): ?IUser
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param ?IUser $user
     *
     * @return self
     */
    public function setUser(?IUser $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * Get the value of calcRecordsAndTotals
     *
     * @return bool
     */
    public function getCalcRecordsAndTotals(): bool
    {
        return $this->calcRecordsAndTotals;
    }

    /**
     * Set the value of calcRecordsAndTotals
     *
     * @param bool $calcRecordsAndTotals
     *
     * @return self
     */
    public function setCalcRecordsAndTotals(bool $calcRecordsAndTotals): self
    {
        $this->calcRecordsAndTotals = $calcRecordsAndTotals;

        return $this;
    }
}
