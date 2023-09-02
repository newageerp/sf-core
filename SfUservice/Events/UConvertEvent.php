<?php

namespace Newageerp\SfUservice\Events;

use Newageerp\SfBaseEntity\Interface\IUser;
use Symfony\Contracts\EventDispatcher\Event;

class UConvertEvent extends Event
{
    public const NAME = 'sfuservice.convertevent';

    protected object $entity;

    protected string $schema;

    protected array $convertOptions;

    protected array $createOptions;

    protected ?IUser $user;

    protected array $dataToReturn = [];

    public function __construct(
        $entity,
        string $schema,
        array $convertOptions,
        array $createOptions,
        ?IUser $user
    ) {
        $this->entity = $entity;
        $this->schema = $schema;
        $this->convertOptions = $convertOptions;
        $this->createOptions = $createOptions;
        $this->user = $user;
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
     * Get the value of convertOptions
     *
     * @return array
     */
    public function getConvertOptions(): array
    {
        return $this->convertOptions;
    }

    /**
     * Set the value of convertOptions
     *
     * @param array $convertOptions
     *
     * @return self
     */
    public function setConvertOptions(array $convertOptions): self
    {
        $this->convertOptions = $convertOptions;

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
     * Get the value of dataToReturn
     *
     * @return array
     */
    public function getDataToReturn(): array
    {
        return $this->dataToReturn;
    }

    /**
     * Set the value of dataToReturn
     *
     * @param array $dataToReturn
     *
     * @return self
     */
    public function setDataToReturn(array $dataToReturn): self
    {
        $this->dataToReturn = $dataToReturn;

        return $this;
    }

    /**
     * Get the value of createOptions
     *
     * @return array
     */
    public function getCreateOptions(): array
    {
        return $this->createOptions;
    }

    /**
     * Set the value of createOptions
     *
     * @param array $createOptions
     *
     * @return self
     */
    public function setCreateOptions(array $createOptions): self
    {
        $this->createOptions = $createOptions;

        return $this;
    }
}
