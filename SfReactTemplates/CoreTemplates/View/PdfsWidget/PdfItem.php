<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View\PdfsWidget;

use Newageerp\SfReactTemplates\Template\Template;

class PdfItem extends Template
{
    protected int $id = 0;
    protected string $schema = '';
    protected string $title = "PDF";
    protected string $template = '';
    protected ?bool $skipStamp = null;
    protected ?bool $skipSign = null;

    public function __construct(int $id, string $schema, string $template, ?string $title = 'PDF')
    {
        $this->id = $id;
        $this->schema = $schema;
        $this->title = $title;
        $this->template = $template;
    }

    public function getProps(): array
    {
        return [
            'id' => $this->getId(),
            'schema' => $this->getSchema(),
            'title' => $this->getTitle(),
            'template' => $this->getTemplate(),
            'skipStamp' => $this->getSkipStamp(),
            'skipSign' => $this->getSkipSign(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'view.pdf.item';
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
     * Get the value of template
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Set the value of template
     *
     * @param string $template
     *
     * @return self
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the value of skipStamp
     *
     * @return ?bool
     */
    public function getSkipStamp(): ?bool
    {
        return $this->skipStamp;
    }

    /**
     * Set the value of skipStamp
     *
     * @param ?bool $skipStamp
     *
     * @return self
     */
    public function setSkipStamp(?bool $skipStamp): self
    {
        $this->skipStamp = $skipStamp;

        return $this;
    }

    /**
     * Get the value of skipSign
     *
     * @return ?bool
     */
    public function getSkipSign(): ?bool
    {
        return $this->skipSign;
    }

    /**
     * Set the value of skipSign
     *
     * @param ?bool $skipSign
     *
     * @return self
     */
    public function setSkipSign(?bool $skipSign): self
    {
        $this->skipSign = $skipSign;

        return $this;
    }
}
