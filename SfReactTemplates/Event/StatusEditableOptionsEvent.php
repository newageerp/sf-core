<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class StatusEditableOptionsEvent extends Event
{
    public const NAME = 'sfreacttemplates.StatusEditableOptionsEvent';

    protected array $options = [];

    protected string $schema = '';

    protected string $type = '';

    protected string $fieldKey = '';

    public function __construct(array $options, string $schema, string $type, string $fieldKey)
    {
        $this->options = $options;
        $this->schema = $schema;
        $this->type = $type;
        $this->fieldKey = $fieldKey;
    }

    /**
     * Get the value of options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get the value of fieldKey
     *
     * @return string
     */
    public function getFieldKey(): string
    {
        return $this->fieldKey;
    }

    /**
     * Set the value of fieldKey
     *
     * @param string $fieldKey
     *
     * @return self
     */
    public function setFieldKey(string $fieldKey): self
    {
        $this->fieldKey = $fieldKey;

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
