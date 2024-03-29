<?php

namespace Newageerp\SfReactTemplates\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfConfig\Service\ConfigService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

/**
 * @Route(path="/app/nae-core/react-templates-actions/")
 */
class ActionsController extends OaBaseController
{
    protected AMQPStreamConnection $connection;

    protected AMQPChannel $channel;

    public function __construct()
    {
        $config = ConfigService::getConfig('mq');

        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );
        $this->channel = $this->connection->channel();
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @Route(path="run/{action}", methods={"POST"})
     */
    public function runAction(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        $queue = $request->get('queue');
        $data = json_encode($request->get('data'));

        $msg = new AMQPMessage($data);
        $this->channel->basic_publish($msg, '', $queue);

        return $this->json(['data' => [], 'success' => 1]);
    }
}
