<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\EditableFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ObjectEditableField extends FormBaseField
{
    protected string $fieldSchema = '';
    protected string $relKey = '';
    protected string $relSchema = '';
    protected ?string $as = null;
    protected ?string $fieldDependency = null;
    protected ?array $fieldExtraSelect = null;
    protected bool $allowCreateRel = false;

    public function __construct(string $key, string $fieldSchema, string $relKey, string $relSchema)
    {
        $this->key = $key;
        $this->fieldSchema = $fieldSchema;
        $this->relKey = $relKey;
        $this->relSchema = $relSchema;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['fieldSchema'] = $this->getFieldSchema();
        $props['relKey'] = $this->getRelKey();
        $props['relSchema'] = $this->getRelSchema();
        $props['as'] = $this->getAs();
        $props['fieldDependency'] = $this->getFieldDependency();
        $props['fieldsExtraSelect'] = $this->getFieldExtraSelect();
        $props['allowCreateRel'] = $this->getAllowCreateRel();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'form.editable.objectfield';
    }

    /**
     * Get the value of fieldSchema
     *
     * @return string
     */
    public function getFieldSchema(): string
    {
        return $this->fieldSchema;
    }

    /**
     * Set the value of fieldSchema
     *
     * @param string $fieldSchema
     *
     * @return self
     */
    public function setFieldSchema(string $fieldSchema): self
    {
        $this->fieldSchema = $fieldSchema;

        return $this;
    }

    /**
     * Get the value of relKey
     *
     * @return string
     */
    public function getRelKey(): string
    {
        return $this->relKey;
    }

    /**
     * Set the value of relKey
     *
     * @param string $relKey
     *
     * @return self
     */
    public function setRelKey(string $relKey): self
    {
        $this->relKey = $relKey;

        return $this;
    }

    /**
     * Get the value of relSchema
     *
     * @return string
     */
    public function getRelSchema(): string
    {
        return $this->relSchema;
    }

    /**
     * Set the value of relSchema
     *
     * @param string $relSchema
     *
     * @return self
     */
    public function setRelSchema(string $relSchema): self
    {
        $this->relSchema = $relSchema;

        return $this;
    }

    /**
     * Get the value of as
     *
     * @return ?string
     */
    public function getAs(): ?string
    {
        return $this->as;
    }

    /**
     * Set the value of as
     *
     * @param ?string $as
     *
     * @return self
     */
    public function setAs(?string $as): self
    {
        $this->as = $as;

        return $this;
    }

    /**
     * Get the value of fieldDependency
     *
     * @return ?string
     */
    public function getFieldDependency(): ?string
    {
        return $this->fieldDependency;
    }

    /**
     * Set the value of fieldDependency
     *
     * @param ?string $fieldDependency
     *
     * @return self
     */
    public function setFieldDependency(?string $fieldDependency): self
    {
        $this->fieldDependency = $fieldDependency;

        return $this;
    }

    /**
     * Get the value of fieldExtraSelect
     *
     * @return ?array
     */
    public function getFieldExtraSelect(): ?array
    {
        return $this->fieldExtraSelect;
    }

    /**
     * Set the value of fieldExtraSelect
     *
     * @param ?array $fieldExtraSelect
     *
     * @return self
     */
    public function setFieldExtraSelect(?array $fieldExtraSelect): self
    {
        $this->fieldExtraSelect = $fieldExtraSelect;

        return $this;
    }

    /**
     * Get the value of allowCreateRel
     *
     * @return bool
     */
    public function getAllowCreateRel(): bool
    {
        return $this->allowCreateRel;
    }

    /**
     * Set the value of allowCreateRel
     *
     * @param bool $allowCreateRel
     *
     * @return self
     */
    public function setAllowCreateRel(bool $allowCreateRel): self
    {
        $this->allowCreateRel = $allowCreateRel;

        return $this;
    }
}
