<?php

namespace Newageerp\SfPushMessages\Object;

use Newageerp\SfPushMessages\Interface\IPushMessage;

class PushMessage implements IPushMessage
{
    protected string $notificationTitle = '';

    protected string $notificationBody = '';

    protected array $notificationData = [];

    /**
     * Get the value of notificationTitle
     *
     * @return string
     */
    public function getNotificationTitle(): string
    {
        return $this->notificationTitle;
    }

    /**
     * Set the value of notificationTitle
     *
     * @param string $notificationTitle
     *
     * @return self
     */
    public function setNotificationTitle(string $notificationTitle): self
    {
        $this->notificationTitle = $notificationTitle;

        return $this;
    }

    /**
     * Get the value of notificationBody
     *
     * @return string
     */
    public function getNotificationBody(): string
    {
        return $this->notificationBody;
    }

    /**
     * Set the value of notificationBody
     *
     * @param string $notificationBody
     *
     * @return self
     */
    public function setNotificationBody(string $notificationBody): self
    {
        $this->notificationBody = $notificationBody;

        return $this;
    }

    /**
     * Get the value of notificationData
     *
     * @return array
     */
    public function getNotificationData(): array
    {
        return $this->notificationData;
    }

    /**
     * Set the value of notificationData
     *
     * @param array $notificationData
     *
     * @return self
     */
    public function setNotificationData(array $notificationData): self
    {
        $this->notificationData = $notificationData;

        return $this;
    }
}
