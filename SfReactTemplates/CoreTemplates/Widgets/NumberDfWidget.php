<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Widgets;

use Newageerp\SfReactTemplates\Template\Template;

class NumberDfWidget extends Template
{
    protected string $childrenPath;
    protected int $elementId;
    protected ?string $currencyPath = null;

    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $currency = null;

    protected ?string $color = null;

    protected ?string $className = null;

    protected bool $isCompact = false;
    

    public function __construct(string $childrenPath, int $elementId, string $currencyPath)
    {
        $this->childrenPath = $childrenPath;
        $this->elementId = $elementId;
        $this->currencyPath = $currencyPath;
    }

    public function getProps(): array
    {
        return [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'currency' => $this->getCurrency(),
            'color' => $this->getColor(),
            'className' => $this->getClassName(),
            'isCompact' => $this->getIsCompact(),

            'childrenPath' => $this->getChildrenPath(),
            'elementId' => $this->getElementId(),
            'currencyPath' => $this->getCurrencyPath(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'widgets.dfnumberwidget';
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


    /**
     * Get the value of childrenPath
     *
     * @return string
     */
    public function getChildrenPath(): string
    {
        return $this->childrenPath;
    }

    /**
     * Set the value of childrenPath
     *
     * @param string $childrenPath
     *
     * @return self
     */
    public function setChildrenPath(string $childrenPath): self
    {
        $this->childrenPath = $childrenPath;

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
     * Get the value of currencyPath
     *
     * @return ?string
     */
    public function getCurrencyPath(): ?string
    {
        return $this->currencyPath;
    }

    /**
     * Set the value of currencyPath
     *
     * @param ?string $currencyPath
     *
     * @return self
     */
    public function setCurrencyPath(?string $currencyPath): self
    {
        $this->currencyPath = $currencyPath;

        return $this;
    }
}
