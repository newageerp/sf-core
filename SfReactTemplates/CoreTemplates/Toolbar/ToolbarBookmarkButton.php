<?php

namespace Newageerp\SfReactTemplates\CoreTemplates\Toolbar;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Newageerp\SfReactTemplates\Template\Template;

class ToolbarBookmarkButton extends Template
{
    protected string $sourceSchema = '';

    protected int $sourceId = 0;

    protected int $user = 0;

    public function __construct(string $sourceSchema, int $sourceId, int $user)
    {
        $this->sourceSchema = $sourceSchema;
        $this->sourceId = $sourceId;
        $this->user = $user;
    }

    public function getProps(): array
    {
        return [
            'sourceSchema' => $this->getSourceSchema(),
            'sourceId' => $this->getSourceId(),
            'user' => $this->getUser(),
        ];
    }

    public function getTemplateName(): string
    {
        return 'toolbar.element-bookmark-button';
    }

    /**
     * Get the value of sourceSchema
     *
     * @return string
     */
    public function getSourceSchema(): string
    {
        return $this->sourceSchema;
    }

    /**
     * Set the value of sourceSchema
     *
     * @param string $sourceSchema
     *
     * @return self
     */
    public function setSourceSchema(string $sourceSchema): self
    {
        $this->sourceSchema = $sourceSchema;

        return $this;
    }

    /**
     * Get the value of sourceId
     *
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * Set the value of sourceId
     *
     * @param int $sourceId
     *
     * @return self
     */
    public function setSourceId(int $sourceId): self
    {
        $this->sourceId = $sourceId;

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
