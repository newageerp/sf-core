<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Table;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class Table extends Template
{
    protected Placeholder $head;
    protected Placeholder $body;

    protected ?string $className = null;

    public function __construct()
    {
        $this->head = new Placeholder();
        $this->body = new Placeholder();
    }

    public function getTemplateName(): string
    {
        return '_.LayoutBundle.Table';
    }

    public function getProps(): array
    {
        return [
            'thead' => $this->getHead()->toArray(),
            'tbody' => $this->getBody()->toArray(),
            'className' => $this->getClassName(),
        ];
    }

    /**
     * Get the value of className
     *
     * @return ?string
     */
    public function getClassName(): ?string
    {
        return $this->className;
    }

    /**
     * Set the value of className
     *
     * @param ?string $className
     *
     * @return self
     */
    public function setClassName(?string $className): self
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get the value of head
     *
     * @return Placeholder
     */
    public function getHead(): Placeholder
    {
        return $this->head;
    }

    /**
     * Set the value of head
     *
     * @param Placeholder $head
     *
     * @return self
     */
    public function setHead(Placeholder $head): self
    {
        $this->head = $head;

        return $this;
    }

    /**
     * Get the value of body
     *
     * @return Placeholder
     */
    public function getBody(): Placeholder
    {
        return $this->body;
    }

    /**
     * Set the value of body
     *
     * @param Placeholder $body
     *
     * @return self
     */
    public function setBody(Placeholder $body): self
    {
        $this->body = $body;

        return $this;
    }
}
