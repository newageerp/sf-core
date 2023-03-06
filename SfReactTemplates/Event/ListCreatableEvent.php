<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class ListCreatableEvent extends Event
{
    public const NAME = 'sfreacttemplates.ListCreatableEvent';

    protected bool $isCreatable;

    protected string $schema;

    public function __construct(
        string $schema,
        bool $isCreatable,
    ) {
        $this->schema = $schema;
        $this->isCreatable = $isCreatable;
    }

    /**
     * Get the value of isCreatable
     *
     * @return bool
     */
    public function getIsCreatable(): bool
    {
        return $this->isCreatable;
    }

    /**
     * Set the value of isCreatable
     *
     * @param bool $isCreatable
     *
     * @return self
     */
    public function setIsCreatable(bool $isCreatable): self
    {
        $this->isCreatable = $isCreatable;

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
