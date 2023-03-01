<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Template;

class OneToOneWidget extends Template
{
    protected string $path = '';
    protected int $id = 0;
    protected string $contentType = '';

    protected array $createScopes = [];
    protected array $removeScopes = [];
    protected array $editScopes = [];
    protected array $showScopes = [];

    protected bool $showOnExist = false;

    public function __construct(
        int $id,
        string $path,
        string $contentType
    ) {
        $this->id = $id;
        $this->path = $path;
        $this->contentType = $contentType;
    }

    public function getProps(): array
    {
        return [
            'id' => $this->getId(),
            'path' => $this->getPath(),
            'contentType' => $this->getContentType(),

            'showScopes' => $this->getShowScopes(),
            'createScopes' => $this->getCreateScopes(),
            'removeScopes' => $this->getRemoveScopes(),
            'editScopes' => $this->getEditScopes(),

            'showOnExist' => $this->getShowOnExist(),
        ];
    }

    public function getTemplateName(): string
    {
        return '_.WidgetsBundle.OneToOneWidget';
    }

    /**
     * Get the value of path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;

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
     * Get the value of contentType
     *
     * @return string
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Set the value of contentType
     *
     * @param string $contentType
     *
     * @return self
     */
    public function setContentType(string $contentType): self
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get the value of createScopes
     *
     * @return array
     */
    public function getCreateScopes(): array
    {
        return $this->createScopes;
    }

    /**
     * Set the value of createScopes
     *
     * @param array $createScopes
     *
     * @return self
     */
    public function setCreateScopes(array $createScopes): self
    {
        $this->createScopes = $createScopes;

        return $this;
    }

    /**
     * Get the value of removeScopes
     *
     * @return array
     */
    public function getRemoveScopes(): array
    {
        return $this->removeScopes;
    }

    /**
     * Set the value of removeScopes
     *
     * @param array $removeScopes
     *
     * @return self
     */
    public function setRemoveScopes(array $removeScopes): self
    {
        $this->removeScopes = $removeScopes;

        return $this;
    }

    /**
     * Get the value of editScopes
     *
     * @return array
     */
    public function getEditScopes(): array
    {
        return $this->editScopes;
    }

    /**
     * Set the value of editScopes
     *
     * @param array $editScopes
     *
     * @return self
     */
    public function setEditScopes(array $editScopes): self
    {
        $this->editScopes = $editScopes;

        return $this;
    }

    /**
     * Get the value of showScopes
     *
     * @return array
     */
    public function getShowScopes(): array
    {
        return $this->showScopes;
    }

    /**
     * Set the value of showScopes
     *
     * @param array $showScopes
     *
     * @return self
     */
    public function setShowScopes(array $showScopes): self
    {
        $this->showScopes = $showScopes;

        return $this;
    }

    /**
     * Get the value of showOnExist
     *
     * @return bool
     */
    public function getShowOnExist(): bool
    {
        return $this->showOnExist;
    }

    /**
     * Set the value of showOnExist
     *
     * @param bool $showOnExist
     *
     * @return self
     */
    public function setShowOnExist(bool $showOnExist): self
    {
        $this->showOnExist = $showOnExist;

        return $this;
    }
}
