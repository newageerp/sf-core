<?php

namespace Newageerp\SfSocket\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfEventListener\EventListener\BaseListener;
use Newageerp\SfEventListener\Events\BgCallbackEvent;
use Newageerp\SfEventListener\Events\BgRequestEvent;
use Newageerp\SfEventListener\Events\OnInsertEvent;
use Newageerp\SfEventListener\Events\OnRemoveEvent;
use Newageerp\SfEventListener\Events\OnUpdateEvent;
use Newageerp\SfSocket\Service\SocketService;
use Psr\Log\LoggerInterface;
use Newageerp\SfSocket\Service\WsUpdateService;

class SfWsUpdateListener extends BaseListener
{
    protected WsUpdateService $wsUpdateService;

    protected SocketService $socketService;

    public function __construct(LoggerInterface $ajLogger, EntityManagerInterface $em, WsUpdateService $wsUpdateService, SocketService $socketService)
    {
        parent::__construct($ajLogger, $em);

        $this->wsUpdateService = $wsUpdateService;
        $this->socketService = $socketService;
    }

    public function onUpdateAll($entity, OnUpdateEvent $event)
    {
        if (!method_exists($entity, 'skipEntityUpdateFromClass')) {
            $event->addRequest(
                new BgRequestEvent($this::class, $entity->getId(), ['entity' => $entity::class])
            );
        }
    }
    public function onInsertAll($entity, OnInsertEvent $event)
    {
        if (!method_exists($entity, 'skipEntityUpdateFromClass')) {
            $event->addRequest(
                new BgRequestEvent($this::class, $entity->getId(), ['entity' => $entity::class])
            );
        }
    }
    public function onRemoveAll($entity, OnRemoveEvent $event)
    {
        // $event->addRequest(
        //     new BgRequestEvent($this::class, $event->getId(), ['entity' => $entity::class])
        // );
        if (!method_exists($entity, 'skipEntityUpdateFromClass')) {
            $this->wsUpdateService->onEntityUpdate($entity);

            $this->socketService->sendPool();
        }
    }

    public function onBgCall(BgCallbackEvent $event)
    {
        if ($event->getId()) {
            $this->wsUpdateService->onEntityUpdateFromClass($event->getData()['entity'], $event->getId());
        }
    }
}
