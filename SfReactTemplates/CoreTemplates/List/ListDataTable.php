<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ListDataTable extends Template
{
    protected Placeholder $header;
    protected Placeholder $row;
    protected ?string $className = null;

    public function __construct()
    {
        $this->header = new Placeholder();
        $this->row = new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'header' => $this->getHeader()->toArray(),
            'row' => $this->getRow()->toArray(),
            'className' => $this->getClassName(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'list.list-data-table';
    }

    /**
     * Get the value of header
     *
     * @return Placeholder
     */
    public function getHeader(): Placeholder
    {
        return $this->header;
    }

    /**
     * Set the value of header
     *
     * @param Placeholder $header
     *
     * @return self
     */
    public function setHeader(Placeholder $header): self
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get the value of row
     *
     * @return Placeholder
     */
    public function getRow(): Placeholder
    {
        return $this->row;
    }

    /**
     * Set the value of row
     *
     * @param Placeholder $row
     *
     * @return self
     */
    public function setRow(Placeholder $row): self
    {
        $this->row = $row;

        return $this;
    }

    /**
     * Get the value of className
     *
     * @return ?string
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * Set the value of className
     *
     * @param ?string $className
     *
     * @return self
     */
    public function setClassName(?string $className): self
    {
        $this->className = $className;

        return $this;
    }
}
