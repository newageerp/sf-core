<?php

namespace Newageerp\SfEventListener\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfEventListener\Events\BgRequestEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfEventListener\Events\OnInsertEvent;
use Newageerp\SfEventListener\Events\OnRemoveEvent;
use Newageerp\SfEventListener\Events\OnUpdateEvent;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class OnFlushEventListener
{
    protected LoggerInterface $ajLogger;

    protected EventDispatcherInterface $evtd;

    protected ?AMQPStreamConnection $connection = null;

    protected ?AMQPChannel $channel = null;

    protected array $insertions = [];
    protected array $updates = [];
    protected array $removes = [];

    public function __destruct()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __construct(
        LoggerInterface $ajLogger,
        EventDispatcherInterface $evtd,
    ) {
        $this->ajLogger = $ajLogger;
        $this->evtd = $evtd;

        $this->insertions = [];
        $this->updates = [];
        $this->removes = [];
    }


    public function getChannel() {
        if (!$this->channel) {
            $config = ConfigService::getConfig('mq');

            $this->connection = new AMQPStreamConnection(
                $config['host'],
                $config['port'],
                $config['user'],
                $config['password']
            );
            $this->channel = $this->connection->channel();
        }
        return $this->channel;
    }

    public function onFlush(OnFlushEventArgs $onFlushEventArgs)
    {
        $em = $onFlushEventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $this->removes[] = ['entity' => $entity, 'id' => $entity->getId()];
        }

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->insertions[] = $entity;
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $changes = $em->getUnitOfWork()->getEntityChangeSet($entity);
            $this->updates[] = ['entity' => $entity, 'changes' => $changes];
        }
    }

    public function postFlush(PostFlushEventArgs $postFlushEventArgs)
    {
        /**
         * @var BgRequestEvent[] $requests
         */
        $requests = [];

        foreach ($this->insertions as $entity) {
            $event = new OnInsertEvent($entity);
            $this->evtd->dispatch($event, OnInsertEvent::NAME);

            $requests = array_merge(
                $requests,
                $event->getRequests()
            );
        }

        foreach ($this->updates as $updateData) {
            $entity = $updateData['entity'];
            $changes = $updateData['changes'];

            $event = new OnUpdateEvent($entity, $changes);
            $this->evtd->dispatch($event, OnUpdateEvent::NAME);

            $requests = array_merge(
                $requests,
                $event->getRequests()
            );
        }

        foreach ($this->removes as $removeData) {
            $entity = $removeData['entity'];
            $id = $removeData['id'];

            $event = new OnRemoveEvent($entity, $id);
            $this->evtd->dispatch($event, OnRemoveEvent::NAME);

            $requests = array_merge(
                $requests,
                $event->getRequests()
            );
        }

        $this->insertions = [];
        $this->updates = [];
        $this->removes = [];

        foreach ($requests as $request) {
            $msg = new AMQPMessage((string)$request);
            $this->getChannel()->basic_publish($msg, '', 'erp.callback');
        }
    }
}
