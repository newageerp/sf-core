<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form\DfRoFields;

use Newageerp\SfReactTemplates\CoreTemplates\Form\FormBaseField;
use Newageerp\SfReactTemplates\CoreTemplates\Form\FormDfBaseField;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ObjectDfRoField extends FormDfBaseField
{
    protected string $idPath = '';
    protected string $relKey = '';
    protected string $relSchema = '';
    protected ?string $as = null;

    public function __construct(string $key, int $id, string $fieldSchema, string $relKey, string $relSchema)
    {
        parent::__construct($key, $id);

        $pathArray = explode(".", $key);
        $pathArray[count($pathArray) - 1] = 'id';
        $idPath = implode(".", $pathArray);

        $this->idPath = $idPath;
        $this->fieldSchema = $fieldSchema;
        $this->relKey = $relKey;
        $this->relSchema = $relSchema;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['idPath'] = $this->getIdPath();
        $props['fieldSchema'] = $this->getFieldSchema();
        $props['relKey'] = $this->getRelKey();
        $props['relSchema'] = $this->getRelSchema();
        $props['as'] = $this->getAs();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'form.dfro.objectfield';
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
     * Get the value of idPath
     *
     * @return string
     */
    public function getIdPath(): string
    {
        return $this->idPath;
    }

    /**
     * Set the value of idPath
     *
     * @param string $idPath
     *
     * @return self
     */
    public function setIdPath(string $idPath): self
    {
        $this->idPath = $idPath;

        return $this;
    }
}
