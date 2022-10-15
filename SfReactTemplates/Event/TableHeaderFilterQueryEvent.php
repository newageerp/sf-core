<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class TableHeaderFilterQueryEvent extends Event
{
    public const NAME = 'sfreacttemplates.TableHeaderFilterQueryEvent';

    protected array $filters = [];

    protected array $prop = [];

    protected string $schema = '';

    protected string $type = '';

    public function __construct(
        array $filters,
        array $prop,
        string $schema,
        string $type,
    ) {
        $this->filters = $filters;
        $this->prop = $prop;
        $this->schema = $schema;
        $this->type = $type;
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
