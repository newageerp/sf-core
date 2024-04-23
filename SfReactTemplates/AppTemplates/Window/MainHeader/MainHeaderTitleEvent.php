<?php

namespace Newageerp\SfReactTemplates\AppTemplates\Window\MainHeader;

use Symfony\Contracts\EventDispatcher\Event;

class MainHeaderTitleEvent extends Event
{
    public const NAME = 'AppTemplatesWindowMainHeaderEvent';

    protected string $title;

    protected array $eventData = [];

    public function __construct(
        string $title,
        array $eventData,
    ) {
        $this->title = $title;
        $this->eventData = $eventData;
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

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
