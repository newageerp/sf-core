<?php

namespace Newageerp\SfReactTemplates\Template;

abstract class Template
{
    protected string $templateId = '';

    protected bool $templateDisabled = false;

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
            'disabled' => $this->getTemplateDisabled(),
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
     * Get the value of templateDisabled
     *
     * @return bool
     */
    public function getTemplateDisabled(): bool
    {
        return $this->templateDisabled;
    }

    /**
     * Set the value of templateDisabled
     *
     * @param bool $templateDisabled
     *
     * @return self
     */
    public function setTemplateDisabled(bool $templateDisabled): self
    {
        $this->templateDisabled = $templateDisabled;

        return $this;
    }
}
