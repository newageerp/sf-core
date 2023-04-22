<?php

namespace Newageerp\SfEventListener\Events;

use Newageerp\SfConfig\Service\ConfigService;

class BgRequestEvent
{
    protected int $id = 0;

    protected array $data = [];

    protected string $event = '';

    public function __construct(
        string $event,
        int $id,
        array $data = []
    ) {
        $this->event = $event;
        $this->id = $id;
        $this->data = $data;
    }

    public function __toString()
    {
        $config = ConfigService::getConfig('main');

        return json_encode(
            [
                'event' => $this->event,
                'id' => $this->id,
                'data' => $this->data,
                'url' => $config['url']
            ]
        );
    }
}
