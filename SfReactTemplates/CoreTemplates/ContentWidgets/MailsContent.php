<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\ContentWidgets;

use Newageerp\SfReactTemplates\Template\Template;

class MailsContent extends Template
{
    protected string $schema = '';

    protected int $id = 0;

    public function __construct(string $schema, int $id)
    {
        $this->schema = $schema;
        $this->id = $id;
    }

    public function getProps(): array
    {
        $props = [
            'schema' => $this->getSchema(),
            'id' => $this->getId(),
        ];

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'content-widgets.MailsContent';
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
}