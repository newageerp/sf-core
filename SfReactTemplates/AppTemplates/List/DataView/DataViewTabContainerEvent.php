<?php

namespace Newageerp\SfReactTemplates\AppTemplates\List\DataView;

use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainer;
use Symfony\Contracts\EventDispatcher\Event;

class DataViewTabContainerEvent extends Event
{
    public const NAME = 'AppTemplatesListDataViewTabContainerEvent';

    protected string $schema = '';
    protected string $type = '';

    protected ?TabContainer $tabContainer = null;

    public function __construct(
        string $schema,
        string $type,
        TabContainer $tabContainer,
    ) {
        $this->schema = $schema;
        $this->type = $type;
        $this->tabContainer = $tabContainer;
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
     * Get the value of tabContainer
     *
     * @return ?TabContainer
     */
    public function getTabContainer(): ?TabContainer
    {
        return $this->tabContainer;
    }

    /**
     * Set the value of tabContainer
     *
     * @param ?TabContainer $tabContainer
     *
     * @return self
     */
    public function setTabContainer(?TabContainer $tabContainer): self
    {
        $this->tabContainer = $tabContainer;

        return $this;
    }
}
