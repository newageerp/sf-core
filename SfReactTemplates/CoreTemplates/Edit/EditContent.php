<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Edit;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class EditContent extends Template
{
    protected string $schema = '';

    protected string $type = '';

    protected string $id = '';

    protected ?object $entity = null;

    protected ?int $defaultViewIndex = null;

    protected EditFormContent $formContent;

    protected ?array $newStateOptions = null;

    public function __construct(string $schema, string $type, string $id, ?object $entity)
    {
        $this->schema = $schema;
        $this->type = $type;
        $this->id = $id;
        $this->entity = $entity;

        $this->formContent = new EditFormContent($schema, $type);
    }

    public function getTemplateData(): array
    {
        return [
            'formContent' => $this->getFormContent()->toArray()
        ];
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'type' => $this->getType(),
            'id' => $this->getId(),
            'defaultViewIndex' => $this->getDefaultViewIndex(),
            'newStateOptions' => $this->getNewStateOptions(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'edit.content';
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
     * Get the value of newStateOptions
     *
     * @return ?array
     */
    public function getNewStateOptions(): ?array
    {
        return $this->newStateOptions;
    }

    /**
     * Set the value of newStateOptions
     *
     * @param ?array $newStateOptions
     *
     * @return self
     */
    public function setNewStateOptions(?array $newStateOptions): self
    {
        $this->newStateOptions = $newStateOptions;

        return $this;
    }

    /**
     * Get the value of formContent
     *
     * @return EditFormContent
     */
    public function getFormContent(): EditFormContent
    {
        return $this->formContent;
    }

    /**
     * Set the value of formContent
     *
     * @param EditFormContent $formContent
     *
     * @return self
     */
    public function setFormContent(EditFormContent $formContent): self
    {
        $this->formContent = $formContent;

        return $this;
    }
}
