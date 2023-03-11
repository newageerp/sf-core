<?php

namespace Newageerp\SfReactTemplates\Template;

abstract class Template
{
    protected string $templateId = '';

    protected bool $disabled = false;

    abstract public function getTemplateName(): string;

    abstract public function getProps(): array;

    public function getTemplateData(): array
    {
        return [];
    }

    public function getAction(): ?string
    {
        return null;
    }

    public function toArray(): array
    {
        return [
            'comp' => $this->getTemplateName(),
            'props' => $this->getProps(),
            'action' => $this->getAction(),
            'templateId' => $this->getTemplateId(),
            'disabled' => $this->getDisabled(),
        ];
    }

    /**
     * Get the value of templateId
     *
     * @return string
     */
    public function getTemplateId(): string
    {
        return $this->templateId;
    }

    /**
     * Set the value of templateId
     *
     * @param string $templateId
     *
     * @return self
     */
    public function setTemplateId(string $templateId): self
    {
        $this->templateId = $templateId;

        return $this;
    }

    /**
     * Get the value of disabled
     *
     * @return bool
     */
    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * Set the value of disabled
     *
     * @param bool $disabled
     *
     * @return self
     */
    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;

        return $this;
    }
}
