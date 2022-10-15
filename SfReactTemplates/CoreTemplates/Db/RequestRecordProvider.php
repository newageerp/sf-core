<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Db;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class RequestRecordProvider extends Template
{
    protected string $schema = '';

    protected string $viewType = '';

    protected string $id = '';

    protected ?bool $showOnEmpty = null;

    protected ?int $defaultViewIndex = null;

    protected Placeholder $children;

    public function __construct(string $schema, string $viewType, string $id)
    {
        $this->schema = $schema;
        $this->viewType = $viewType;
        $this->id = $id;

        $this->children = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'viewType' => $this->getViewType(),
            'id' => $this->getId(),
            'showOnEmpty' => $this->getShowOnEmpty(),
            'defaultViewIndex' => $this->getDefaultViewIndex(),
            'children' => $this->getChildren()->toArray(),
        ];
    }


    public function getTemplateName(): string
    {
        return 'db.request.recordprovider';
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
     * Get the value of viewType
     *
     * @return string
     */
    public function getViewType(): string
    {
        return $this->viewType;
    }

    /**
     * Set the value of viewType
     *
     * @param string $viewType
     *
     * @return self
     */
    public function setViewType(string $viewType): self
    {
        $this->viewType = $viewType;

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
     * Get the value of showOnEmpty
     *
     * @return ?bool
     */
    public function getShowOnEmpty(): ?bool
    {
        return $this->showOnEmpty;
    }

    /**
     * Set the value of showOnEmpty
     *
     * @param ?bool $showOnEmpty
     *
     * @return self
     */
    public function setShowOnEmpty(?bool $showOnEmpty): self
    {
        $this->showOnEmpty = $showOnEmpty;

        return $this;
    }

    /**
     * Get the value of defaultViewIndex
     *
     * @return ?int
     */
    public function getDefaultViewIndex(): ?int
    {
        return $this->defaultViewIndex;
    }

    /**
     * Set the value of defaultViewIndex
     *
     * @param ?int $defaultViewIndex
     *
     * @return self
     */
    public function setDefaultViewIndex(?int $defaultViewIndex): self
    {
        $this->defaultViewIndex = $defaultViewIndex;

        return $this;
    }

    /**
     * Get the value of children
     *
     * @return Placeholder
     */
    public function getChildren(): Placeholder
    {
        return $this->children;
    }

    /**
     * Set the value of children
     *
     * @param Placeholder $children
     *
     * @return self
     */
    public function setChildren(Placeholder $children): self
    {
        $this->children = $children;

        return $this;
    }
}