<?php

namespace Newageerp\SfControlpanel\Event;

use Newageerp\SfReactTemplates\Template\Placeholder;
use Symfony\Contracts\EventDispatcher\Event;

class FilterPropertiesEvent extends Event
{
    public const NAME = 'sfcontrolpanel.FilterPropertiesEvent';

    protected array $fields;

    public function __construct(array $fields,) {
        $this->fields = $fields;
    }

    /**
     * Get the value of fields
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the value of fields
     *
     * @param array $fields
     *
     * @return self
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }
}
