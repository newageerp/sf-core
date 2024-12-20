<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Table;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class TableTd extends Template
{
    protected Placeholder $contents;

    protected ?string $textAlignment = null;

    protected ?string $className = null;

    protected ?string $dataType = null;


    public function __construct(?Placeholder $contents = null)
    {
        $this->contents = $contents ? $contents : new Placeholder();
    }

    public function getTemplateName(): string
    {
        return '_.LayoutBundle.Td';
    }

    public function getProps(): array
    {
        return [
            'children' => $this->getContents()->toArray(),
            'textAlignment' => $this->getTextAlignment(),
            'className' => $this->getClassName(),
            'dataType' => $this->getDataType(),
        ];
    }


    /**
     * Get the value of textAlignment
     *
     * @return ?string
     */
    public function getTextAlignment(): ?string
    {
        return $this->textAlignment;
    }

    /**
     * Set the value of textAlignment
     *
     * @param ?string $textAlignment
     *
     * @return self
     */
    public function setTextAlignment(?string $textAlignment): self
    {
        $this->textAlignment = $textAlignment;

        return $this;
    }

    /**
     * Get the value of contents
     *
     * @return Placeholder
     */
    public function getContents(): Placeholder
    {
        return $this->contents;
    }

    /**
     * Set the value of contents
     *
     * @param Placeholder $contents
     *
     * @return self
     */
    public function setContents(Placeholder $contents): self
    {
        $this->contents = $contents;

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

    /**
     * Get the value of dataType
     *
     * @return ?string
     */
    public function getDataType(): ?string
    {
        return $this->dataType;
    }

    /**
     * Set the value of dataType
     *
     * @param ?string $dataType
     *
     * @return self
     */
    public function setDataType(?string $dataType): self
    {
        $this->dataType = $dataType;

        return $this;
    }
}
