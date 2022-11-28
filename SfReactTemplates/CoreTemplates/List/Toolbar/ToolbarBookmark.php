<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\List\Toolbar;

use Newageerp\SfReactTemplates\Template\Template;

class ToolbarBookmark extends Template
{
    protected string $schema = '';

    protected int $user = 0;

    public function __construct(string $schema, int $user)
    {
        $this->schema = $schema;
        $this->user = $user;
    }

    public function getProps(): array
    {
        return [
            'schema' => $this->getSchema(),
            'user' => $this->getUser(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'list.toolbar.bookmark';
    }

    /**
     * Get the value of schema
     *
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @param string $schema
     *
     * @return self
     */
    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get the value of user
     *
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param int $user
     *
     * @return self
     */
    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }
}

