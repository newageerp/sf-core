<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class EditEnumTextEditableFieldListEvent extends Event
{
    public const NAME = 'sfreacttemplates.EditEnumTextEditableFieldList';

    protected string $schema = '';

    protected string $type = '';

    protected string $fieldKey = '';

    protected array $options = [];

    public function __construct(
        string $schema,
        string $type,
        string $fieldKey,
        array $options,
    ) {
        $this->schema = $schema;
        $this->type = $type;
        $this->fieldKey = $fieldKey;
        $this->options = $options;
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
}
