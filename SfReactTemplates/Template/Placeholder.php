<?php

namespace Newageerp\SfReactTemplates\Template;

class Placeholder
{
    /**
     * @var Template[] $templates
     */
    protected array $templates = [];

    protected array $placeholderTemplatesData = [];

    public function addTemplate(Template $template)
    {
        $this->templates[] = $template;
    }

    /**
     * Get the value of templates
     *
     * @return array
     */
    public function getTemplates(): array
    {
        return $this->templates;
    }

    /**
     * Set the value of templates
     *
     * @param array $templates
     *
     * @return self
     */
    public function setTemplates(array $templates): self
    {
        $this->templates = $templates;

        return $this;
    }

    public function toArray(): array
    {
        $data = array_map(
            function (Template $t) {
                return $t->toArray();
            },
            $this->templates
        );
        return $data;
    }

    public function getTemplatesData(): array
    {
        /**
         * @var array $data
         */
        $data = array_map(
            function (Template $t) {
                return $t->getTemplateData();
            },
            $this->templates
        );
        return array_merge($this->getPlaceholderTemplatesData(), ...$data);
    }

    /**
     * Get the value of placeholderTemplatesData
     *
     * @return array
     */
    public function getPlaceholderTemplatesData(): array
    {
        return $this->placeholderTemplatesData;
    }

    /**
     * Set the value of placeholderTemplatesData
     *
     * @param array $placeholderTemplatesData
     *
     * @return self
     */
    public function setPlaceholderTemplatesData(array $placeholderTemplatesData): self
    {
        $this->placeholderTemplatesData = $placeholderTemplatesData;

        return $this;
    }
}
