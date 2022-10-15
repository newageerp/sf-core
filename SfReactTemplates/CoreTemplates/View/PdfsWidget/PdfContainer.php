<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\View\PdfsWidget;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class PdfContainer extends Template
{
    protected ?string $title = null;

    protected Placeholder $items;

    public function __construct(?string $title = null, ?Placeholder $items = null)
    {
        $this->title = $title;
        $this->items = $items ? $items : new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'items' => $this->getItems()->toArray(),
            'title' => $this->getTitle(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'view.pdf.container';
    }

    /**
     * Get the value of items
     *
     * @return Placeholder
     */
    public function getItems(): Placeholder
    {
        return $this->items;
    }

    /**
     * Set the value of items
     *
     * @param Placeholder $items
     *
     * @return self
     */
    public function setItems(Placeholder $items): self
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get the value of title
     *
     * @return ?string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param ?string $title
     *
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
