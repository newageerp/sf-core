<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Edit;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class EditContentInlineTemplate extends Template
{
    protected string $schema = '';

    protected string $type = '';

    protected string $id = '';

    protected bool $isCompact = false;

    public function __construct(string $schema, string $type, string $id)
    {
        $this->schema = $schema;
        $this->type = $type;
        $this->id = $id;
        
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'type' => $this->getType(),
            'id' => $this->getId(),
            'isCompact' => $this->getIsCompact()
        ];
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.MainEditContentInline';
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

    /**
     * Get the value of id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param string $id
     *
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of isCompact
     *
     * @return bool
     */
    public function getIsCompact(): bool
    {
        return $this->isCompact;
    }

    /**
     * Set the value of isCompact
     *
     * @param bool $isCompact
     *
     * @return self
     */
    public function setIsCompact(bool $isCompact): self
    {
        $this->isCompact = $isCompact;

        return $this;
    }
}
