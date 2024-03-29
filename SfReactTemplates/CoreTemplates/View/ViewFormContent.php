<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ViewFormContent extends Template
{
    protected string $schema = '';

    protected string $type = '';

    protected Placeholder $content;

    protected bool $isCompact = false;

    protected array $scopes = [];

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
            'scopes' => $this->getScopes(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.MainViewFormContent';
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
     * Get the value of scopes
     *
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Set the value of scopes
     *
     * @param array $scopes
     *
     * @return self
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }
}
