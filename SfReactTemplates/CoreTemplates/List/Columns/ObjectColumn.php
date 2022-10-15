<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Columns;

use Newageerp\SfReactTemplates\CoreTemplates\List\ListBaseColumn;

class ObjectColumn extends ListBaseColumn {
    protected string $idKey = '';
    protected string $relSchema = '';
    protected ?string $as = null;
    protected ?string $hasLink = null;

    public function __construct(string $key, string $idKey, string $relSchema)
    {
        parent::__construct($key);
        $this->idKey = $idKey;
        $this->relSchema = $relSchema;
    }

    public function getProps(): array
    {
        $props = parent::getProps();

        $props['idKey'] = $this->getIdKey();
        $props['relSchema'] = $this->getRelSchema();
        $props['as'] = $this->getAs();
        $props['hasLink'] = $this->getHasLink();

        return $props;
    }

    public function getTemplateName(): string
    {
        return 'list.ro.objectcolumn';
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
     * Get the value of hasLink
     *
     * @return ?string
     */
    public function getHasLink(): ?string
    {
        return $this->hasLink;
    }

    /**
     * Set the value of hasLink
     *
     * @param ?string $hasLink
     *
     * @return self
     */
    public function setHasLink(?string $hasLink): self
    {
        $this->hasLink = $hasLink;

        return $this;
    }

    /**
     * Get the value of idKey
     *
     * @return string
     */
    public function getIdKey(): string
    {
        return $this->idKey;
    }

    /**
     * Set the value of idKey
     *
     * @param string $idKey
     *
     * @return self
     */
    public function setIdKey(string $idKey): self
    {
        $this->idKey = $idKey;

        return $this;
    }
}