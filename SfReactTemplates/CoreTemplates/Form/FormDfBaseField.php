<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Form;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

abstract class FormDfBaseField extends Template
{
    protected string $key = '';

    protected int $id = 0;

    public function __construct(string $key, int $id)
    {
        $this->key = $key;
        $this->id = $id;
    }

    public function getProps(): array
    {
        return [
            'fieldKey' => $this->getKey(),
            'id' => $this->getId(),
        ];
    }


    /**
     * Get the value of key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Set the value of key
     *
     * @param string $key
     *
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
