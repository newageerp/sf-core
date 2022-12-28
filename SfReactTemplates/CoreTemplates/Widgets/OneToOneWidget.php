<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Template;

class OneToOneWidget extends Template
{
    protected string $path = '';
    protected int $id = 0;
    protected string $contentType = '';

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
        ];
    }

    public function getTemplateName(): string
    {
        return 'widgets.oneToOneWidget';
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
}
