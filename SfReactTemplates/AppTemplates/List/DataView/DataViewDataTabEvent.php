<?php

namespace Newageerp\SfReactTemplates\AppTemplates\List\DataView;

use Newageerp\SfReactTemplates\CoreTemplates\Tabs\TabContainerItem;
use Symfony\Contracts\EventDispatcher\Event;

class DataViewDataTabEvent extends Event
{
    public const NAME = 'AppTemplatesListDataViewDataTabEvent';

    protected string $schema = '';
    protected string $type = '';

    protected array $eventData = [];

    protected ?TabContainerItem $tabContainerItem = null;

    public function __construct(
        string $schema,
        string $type,
        array $eventData,
        TabContainerItem $tabContainerItem,
    ) {
        $this->schema = $schema;
        $this->type = $type;
        $this->eventData = $eventData;
        $this->tabContainerItem = $tabContainerItem;
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
     * Get the value of tabContainerItem
     *
     * @return ?TabContainerItem
     */
    public function getTabContainerItem(): ?TabContainerItem
    {
        return $this->tabContainerItem;
    }

    /**
     * Set the value of tabContainerItem
     *
     * @param ?TabContainerItem $tabContainerItem
     *
     * @return self
     */
    public function setTabContainerItem(?TabContainerItem $tabContainerItem): self
    {
        $this->tabContainerItem = $tabContainerItem;

        return $this;
    }

    /**
     * Get the value of eventData
     *
     * @return array
     */
    public function getEventData(): array
    {
        return $this->eventData;
    }

    /**
     * Set the value of eventData
     *
     * @param array $eventData
     *
     * @return self
     */
    public function setEventData(array $eventData): self
    {
        $this->eventData = $eventData;

        return $this;
    }
}
