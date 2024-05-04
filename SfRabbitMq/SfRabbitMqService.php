<?php

namespace Newageerp\SfRabbitMq;

use Newageerp\SfConfig\Service\ConfigService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class SocketService
{
    protected array $config = [];

    protected LoggerInterface $ajLogger;

    protected ?AMQPStreamConnection $connection = null;

    protected ?AMQPChannel $channel = null;

    protected $pool = [];

    protected string $queueName = '';

    public function __construct(LoggerInterface $ajLogger)
    {
        $this->ajLogger = $ajLogger;
    }

    public function parseConfig($configName)
    {
        $config = ConfigService::getConfig($configName);
        if (isset($config['host'])) {
            $this->config = $config;
        }
    }

    public function reconnect()
    {
        $this->connection = new AMQPStreamConnection(
            $this->config['host'],
            $this->config['port'],
            $this->config['user'],
            $this->config['password']
        );
        $this->channel = $this->connection->channel();
    }

    public function closeConnection() {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function getChannel()
    {
        if (!$this->channel) {
            if (isset($this->config['host'])) {
                $this->reconnect();
            }
            if (isset($config['queue'])) {
                $this->queueName = $config['queue'];
            }
        }
        return $this->channel;
    }

    public function __destruct()
    {
        $this->closeConnection();
    }

    public function addToPool($data)
    {
        $this->pool[] = $data;
    }

    public function clearPool()
    {
        $this->pool = [];
    }

    public function sendToQueue(string $message, string $queueName)
    {
        $msg = new AMQPMessage($message);
        $this->getChannel()->basic_publish($msg, '', $queueName);
    }

    public function sendPool()
    {
        $count = count($this->pool);
        if ($count === 0) {
            return 0;
        }
        if (!$this->getChannel()) {
            return 0;
        }

        foreach ($this->pool as $el) {
            $this->sendToQueue(json_encode($el), $this->queueName);
        }

        $this->clearPool();
        return $count;
    }
}
