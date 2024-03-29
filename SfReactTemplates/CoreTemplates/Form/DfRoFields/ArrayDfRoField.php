<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormDfBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ArrayDfRoField extends FormDfBaseField
{
    protected string $tabSchema = '';
    protected string $tabType = '';

    public function __construct(string $key, int $id, string $tabSchema, string $tabType)
    {
        parent::__construct($key, $id);

        $this->tabSchema = $tabSchema;
        $this->tabType = $tabType;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['tabSchema'] = $this->getTabSchema();
        $props['tabType'] = $this->getTabType();

        return $props;
    }

    public function getTemplateName(): string
    {
        return '_.AppBundle.ArrayDfRoField';
    }

    /**
     * Get the value of tabSchema
     *
     * @return string
     */
    public function getTabSchema(): string
    {
        return $this->tabSchema;
    }

    /**
     * Set the value of tabSchema
     *
     * @param string $tabSchema
     *
     * @return self
     */
    public function setTabSchema(string $tabSchema): self
    {
        $this->tabSchema = $tabSchema;

        return $this;
    }

    /**
     * Get the value of tabType
     *
     * @return string
     */
    public function getTabType(): string
    {
        return $this->tabType;
    }

    /**
     * Set the value of tabType
     *
     * @param string $tabType
     *
     * @return self
     */
    public function setTabType(string $tabType): self
    {
        $this->tabType = $tabType;

        return $this;
    }
}
