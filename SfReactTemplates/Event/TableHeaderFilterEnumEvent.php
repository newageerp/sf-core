<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class TableHeaderFilterEnumEvent extends Event
{
    public const NAME = 'sfreacttemplates.TableHeaderFilterEnumEvent';

    protected array $enums = [];

    protected array $prop = [];

    protected string $schema = '';

    protected string $type = '';

    public function __construct(
        array $enums,
        array $prop,
        string $schema,
        string $type,
    ) {
        $this->enums = $enums;
        $this->prop = $prop;
        $this->schema = $schema;
        $this->type = $type;
    }

    /**
     * Get the value of enums
     *
     * @return array
     */
    public function getEnums(): array
    {
        return $this->enums;
    }

    /**
     * Set the value of enums
     *
     * @param array $enums
     *
     * @return self
     */
    public function setEnums(array $enums): self
    {
        $this->enums = $enums;

        return $this;
    }

    /**
     * Get the value of prop
     *
     * @return array
     */
    public function getProp(): array
    {
        return $this->prop;
    }

    /**
     * Set the value of prop
     *
     * @param array $prop
     *
     * @return self
     */
    public function setProp(array $prop): self
    {
        $this->prop = $prop;

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
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
