<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\Rows;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class CompactRow extends Template
{
    protected ?string $labelClassName = null;

    protected bool $labelAutoWidth = false;

    protected ?string $controlClassName = null;

    protected ?array $fieldVisibilityData = null;

    protected Placeholder $labelContent;

    protected Placeholder $controlContent;

    public function __construct(?Placeholder $labelContent = null, ?Placeholder $controlContent = null)
    {
        $this->labelContent = $labelContent ? $labelContent : new Placeholder();
        $this->controlContent = $controlContent ? $controlContent : new Placeholder();
    }

    public function getProps(): array
    {
        return [
            'labelClassName' => $this->getLabelClassName(),
            'labelAutoWidth' => $this->getLabelAutoWidth(),
            'controlClassName' => $this->getControlClassName(),
            'labelContent' => $this->getLabelContent()->toArray(),
            'controlContent' => $this->getControlContent()->toArray(),
            'fieldVisibilityData' => $this->getFieldVisibilityData(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'form.rows.compactrow';
    }

    /**
     * Get the value of labelClassName
     *
     * @return ?string
     */
    public function getLabelClassName(): ?string
    {
        return $this->labelClassName;
    }

    /**
     * Set the value of labelClassName
     *
     * @param ?string $labelClassName
     *
     * @return self
     */
    public function setLabelClassName(?string $labelClassName): self
    {
        $this->labelClassName = $labelClassName;

        return $this;
    }

    /**
     * Get the value of labelAutoWidth
     *
     * @return bool
     */
    public function getLabelAutoWidth(): bool
    {
        return $this->labelAutoWidth;
    }

    /**
     * Set the value of labelAutoWidth
     *
     * @param bool $labelAutoWidth
     *
     * @return self
     */
    public function setLabelAutoWidth(bool $labelAutoWidth): self
    {
        $this->labelAutoWidth = $labelAutoWidth;

        return $this;
    }

    /**
     * Get the value of controlClassName
     *
     * @return ?string
     */
    public function getControlClassName(): ?string
    {
        return $this->controlClassName;
    }

    /**
     * Set the value of controlClassName
     *
     * @param ?string $controlClassName
     *
     * @return self
     */
    public function setControlClassName(?string $controlClassName): self
    {
        $this->controlClassName = $controlClassName;

        return $this;
    }

    /**
     * Get the value of labelContent
     *
     * @return ?Placeholder
     */
    public function getLabelContent(): ?Placeholder
    {
        return $this->labelContent;
    }

    /**
     * Set the value of labelContent
     *
     * @param ?Placeholder $labelContent
     *
     * @return self
     */
    public function setLabelContent(?Placeholder $labelContent): self
    {
        $this->labelContent = $labelContent;

        return $this;
    }

    /**
     * Get the value of controlContent
     *
     * @return ?Placeholder
     */
    public function getControlContent(): ?Placeholder
    {
        return $this->controlContent;
    }

    /**
     * Set the value of controlContent
     *
     * @param ?Placeholder $controlContent
     *
     * @return self
     */
    public function setControlContent(?Placeholder $controlContent): self
    {
        $this->controlContent = $controlContent;

        return $this;
    }

    /**
     * Get the value of fieldVisibilityData
     *
     * @return ?array
     */
    public function getFieldVisibilityData(): ?array
    {
        return $this->fieldVisibilityData;
    }

    /**
     * Set the value of fieldVisibilityData
     *
     * @param ?array $fieldVisibilityData
     *
     * @return self
     */
    public function setFieldVisibilityData(?array $fieldVisibilityData): self
    {
        $this->fieldVisibilityData = $fieldVisibilityData;

        return $this;
    }
}
