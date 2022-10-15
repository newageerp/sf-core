<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class LoadTemplateEvent extends Event
{
    public const NAME = 'sfreacttemplates.loadtemplate';

    protected string $templateName = '';

    protected array $data = [];

    protected Placeholder $placeholder;

    public function __construct(
        Placeholder $placeholder,
        string $templateName,
        array $data,
    ) {
        $this->placeholder = $placeholder;
        $this->templateName = $templateName;
        $this->data = $data;
    }

    public function isTemplateForAnyEntity(string $templateName): bool
    {
        if (
            $this->getTemplateName() === $templateName
        ) {
            return true;
        }
        return false;
    }

    public function isTemplateForEntity(string $templateName, string $entity): bool
    {
        if (
            isset($this->data['schema']) && $this->data['schema'] === $entity &&
            $this->getTemplateName() === $templateName
        ) {
            return true;
        }
        return false;
    }


    /**
     * Get the value of templateName
     *
     * @return string
     */
    public function getTemplateName(): string
    {
        return $this->templateName;
    }

    /**
     * Set the value of templateName
     *
     * @param string $templateName
     *
     * @return self
     */
    public function setTemplateName(string $templateName): self
    {
        $this->templateName = $templateName;

        return $this;
    }

    /**
     * Get the value of data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @param array $data
     *
     * @return self
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of placeholder
     *
     * @return Placeholder
     */
    public function getPlaceholder(): Placeholder
    {
        return $this->placeholder;
    }

    /**
     * Set the value of placeholder
     *
     * @param Placeholder $placeholder
     *
     * @return self
     */
    public function setPlaceholder(Placeholder $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }
}
