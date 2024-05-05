<?php

namespace Newageerp\SfSocket\Service;

use Newageerp\SfConfig\Service\ConfigService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class SocketService
{
    protected LoggerInterface $ajLogger;

    protected ?AMQPStreamConnection $connection = null;

    protected ?AMQPChannel $channel = null;

    protected $pool = [];

    protected string $queueName = '';

    public function __construct(LoggerInterface $ajLogger)
    {
        $this->ajLogger = $ajLogger;
    }

    public function getChannel()
    {
        if (!$this->channel) {
            $timeStart = microtime(true);
            $config = ConfigService::getConfig('mq');
            if (isset($config['host'])) {
                $this->connection = new AMQPStreamConnection(
                    $config['host'],
                    $config['port'],
                    $config['user'],
                    $config['password']
                );
                $this->channel = $this->connection->channel();

                $this->queueName = $config['queue'];
            }
            $timeFinish = microtime(true);
            // $this->ajLogger->warning('AMQP connect' . number_format($timeFinish - $timeStart, 5));
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
