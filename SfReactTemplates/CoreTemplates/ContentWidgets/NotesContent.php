<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\ContentWidgets;

use Newageerp\SfReactTemplates\Template\Template;

class NotesContent extends Template
{
    protected bool $showOnlyMy = false;

    protected string $schema = '';

    protected int $id = 0;

    protected ?array $options = [];

    public function __construct(string $schema, int $id)
    {
        $this->schema = $schema;
        $this->id = $id;
    }

    public function getProps(): array
    {
        $props = [
            'showOnlyMy' => $this->getShowOnlyMy(),
            'schema' => $this->getSchema(),
            'id' => $this->getId(),
            'options' => $this->getOptions(),
        ];

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.CustomNotesApp.NotesContent';
    }

    /**
     * Get the value of showOnlyMy
     *
     * @return bool
     */
    public function getShowOnlyMy(): bool
    {
        return $this->showOnlyMy;
    }

    /**
     * Set the value of showOnlyMy
     *
     * @param bool $showOnlyMy
     *
     * @return self
     */
    public function setShowOnlyMy(bool $showOnlyMy): self
    {
        $this->showOnlyMy = $showOnlyMy;

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
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of options
     *
     * @return ?array
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Set the value of options
     *
     * @param ?array $options
     *
     * @return self
     */
    public function setOptions(?array $options): self
    {
        $this->options = $options;

        return $this;
    }
}
