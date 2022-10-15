<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View\FilesWidget;

class FilesWidgetItem
{
    protected string $title = '';

    protected string $folder = '';

    protected string $entity = '';

    protected string $hint = '';

    protected bool $allowUpload = true;

    protected int $elementId = 0;

    protected array $actions = ['view', 'download'];

    public function __construct(string $title, string $entity, string $folder, int $elementId)
    {
        $this->title = $title;
        $this->entity = $entity;
        $this->folder = $folder;
        $this->elementId = $elementId;
    }

    public function toArray()
    {
        return [
            'title' => $this->getTitle(),
            'folder' => $this->getEntity() . '/' . $this->getElementId() . '/' . $this->getFolder(),
            'hint' => $this->getHint(),
            'disableUpload' => !$this->getAllowUpload(),
            'actions' => $this->getActions(),
        ];
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of folder
     *
     * @return string
     */
    public function getFolder(): string
    {
        return $this->folder;
    }

    /**
     * Set the value of folder
     *
     * @param string $folder
     *
     * @return self
     */
    public function setFolder(string $folder): self
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get the value of entity
     *
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * Set the value of entity
     *
     * @param string $entity
     *
     * @return self
     */
    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get the value of hint
     *
     * @return string
     */
    public function getHint(): string
    {
        return $this->hint;
    }

    /**
     * Set the value of hint
     *
     * @param string $hint
     *
     * @return self
     */
    public function setHint(string $hint): self
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * Get the value of allowUpload
     *
     * @return bool
     */
    public function getAllowUpload(): bool
    {
        return $this->allowUpload;
    }

    /**
     * Set the value of allowUpload
     *
     * @param bool $allowUpload
     *
     * @return self
     */
    public function setAllowUpload(bool $allowUpload): self
    {
        $this->allowUpload = $allowUpload;

        return $this;
    }

    /**
     * Get the value of elementId
     *
     * @return int
     */
    public function getElementId(): int
    {
        return $this->elementId;
    }

    /**
     * Set the value of elementId
     *
     * @param int $elementId
     *
     * @return self
     */
    public function setElementId(int $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
    }

    /**
     * Get the value of actions
     *
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Set the value of actions
     *
     * @param array $actions
     *
     * @return self
     */
    public function setActions(array $actions): self
    {
        $this->actions = $actions;

        return $this;
    }
}
