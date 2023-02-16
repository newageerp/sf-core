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

    protected string $icon;

    public function __construct(
        string $schema,
        string $type,
        string $icon,
    ) {
        $this->schema = $schema;
        $this->type = $type;
        $this->icon = $icon;
    }

    /**
     * Get the value of schema
     */ 
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * Set the value of schema
     *
     * @return  self
     */ 
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of icon
     */ 
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @return  self
     */ 
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get the value of enable
     */ 
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * Set the value of enable
     *
     * @return  self
     */ 
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }
}
