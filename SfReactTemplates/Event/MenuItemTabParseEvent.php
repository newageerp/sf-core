<?php

namespace Newageerp\SfReactTemplates\Event;

use Newageerp\SfReactTemplates\CoreTemplates\Table\TableTd;
use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class MenuItemTabParseEvent extends Event
{
    public const NAME = 'sfreacttemplates.MenuItemTabParseEvent';

    protected bool $enable = true;

    protected string $schema;

    protected string $type;

    protected ?string $icon;

    public function __construct(
        string $schema,
        string $type,
        ?string $icon,
    ) {
        $this->schema = $schema;
        $this->type = $type;
        $this->icon = $icon;
    }


    /**
     * Get the value of enable
     *
     * @return bool
     */
    public function getEnable(): bool
    {
        return $this->enable;
    }

    /**
     * Set the value of enable
     *
     * @param bool $enable
     *
     * @return self
     */
    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
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
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param string $type
     *
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of icon
     *
     * @return ?string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @param ?string $icon
     *
     * @return self
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
}
