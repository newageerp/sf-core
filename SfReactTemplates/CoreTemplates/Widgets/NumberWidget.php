<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Template;

class NumberWidget extends Template
{
    protected ?string $title = null;

    protected ?string $description = null;

    protected ?float $floatNumber = null;

    protected ?int $intNumber = null;

    protected ?string $currency = null;

    protected ?string $color = null;

    protected ?string $className = null;

    protected bool $isCompact = false;

    public function __construct()
    {
    }

    public function getProps(): array
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'children' => $this->getFloatNumber() !== null ? $this->getFloatNumber() : $this->getIntNumber(),
            'asFloat' => $this->getFloatNumber() !== null,
            'currency' => $this->getCurrency(),
            'color' => $this->getColor(),
            'className' => $this->getClassName(),
            'isCompact' => $this->getIsCompact(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'widgets.numberwidget';
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

    /**
     * Get the value of description
     *
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param ?string $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of floatNumber
     *
     * @return ?float
     */
    public function getFloatNumber(): ?float
    {
        return $this->floatNumber;
    }

    /**
     * Set the value of floatNumber
     *
     * @param ?float $floatNumber
     *
     * @return self
     */
    public function setFloatNumber(?float $floatNumber): self
    {
        $this->floatNumber = $floatNumber;

        return $this;
    }


    /**
     * Get the value of intNumber
     *
     * @return ?int
     */
    public function getIntNumber(): ?int
    {
        return $this->intNumber;
    }

    /**
     * Set the value of intNumber
     *
     * @param ?int $intNumber
     *
     * @return self
     */
    public function setIntNumber(?int $intNumber): self
    {
        $this->intNumber = $intNumber;

        return $this;
    }

    /**
     * Get the value of currency
     *
     * @return ?string
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @param ?string $currency
     *
     * @return self
     */
    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of color
     *
     * @return ?string
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * Set the value of color
     *
     * @param ?string $color
     *
     * @return self
     */
    public function setColor(?string $color): self
    {
        $this->color = $color;

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

}
