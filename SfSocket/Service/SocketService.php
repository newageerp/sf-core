<?php

namespace Newageerp\SfSocket\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class SocketService
{
    protected ?AMQPStreamConnection $connection = null;

    protected ?AMQPChannel $channel = null;

    protected $pool = [];

    public function __construct()
    {
    }

    public function getChannel()
    {
        if (!$this->channel) {
            $this->connection = new AMQPStreamConnection(
                $_ENV['NAE_SFS_RBQ_HOST'],
                (int)$_ENV['NAE_SFS_RBQ_PORT'],
                $_ENV['NAE_SFS_RBQ_USER'],
                $_ENV['NAE_SFS_RBQ_PASSWORD']
            );
            $this->channel = $this->connection->channel();
        }
        return $this->channel;
    }

    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function sendTo($room, $action, $data)
    {
        $this->client->emit(
            'send',
            [
                'room' => $room,
                'payload' => [
                    'action' => $action,
                    'data' => $data
                ]
            ]
        );
    }

    public function addToPool($data)
    {
        $this->pool[] = $data;
    }

    public function clearPool()
    {
        $this->pool = [];
    }

    public function sendPool()
    {
        $count = count($this->pool);

        foreach ($this->pool as $el) {
            $msg = new AMQPMessage(json_encode($el));
            $this->getChannel()->basic_publish($msg, '', $_ENV['NAE_SFS_RBQ_QUEUE']);
        }

        $this->clearPool();
        return $count;
    }
}
