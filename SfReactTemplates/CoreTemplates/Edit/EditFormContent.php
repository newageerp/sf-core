<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Edit;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class EditFormContent extends Template
{
    protected string $schema = '';

    protected string $type = '';

    protected Placeholder $content;

    protected bool $isCompact = false;

    protected ?array $parentElement = null;

    public function __construct(string $schema, string $type)
    {
        $this->schema = $schema;
        $this->type = $type;

        $this->content = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getContent()->toArray(),
            'schema' => $this->getSchema(),
            'type' => $this->getType(),
            'isCompact' => $this->getIsCompact(),
            'parentElement' => $this->getParentElement(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'edit.formcontent';
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
     * Get the value of content
     *
     * @return Placeholder
     */
    public function getContent(): Placeholder
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param Placeholder $content
     *
     * @return self
     */
    public function setContent(Placeholder $content): self
    {
        $this->content = $content;

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

    /**
     * Get the value of parentElement
     *
     * @return ?array
     */
    public function getParentElement(): ?array
    {
        return $this->parentElement;
    }

    /**
     * Set the value of parentElement
     *
     * @param ?array $parentElement
     *
     * @return self
     */
    public function setParentElement(?array $parentElement): self
    {
        $this->parentElement = $parentElement;

        return $this;
    }
}
