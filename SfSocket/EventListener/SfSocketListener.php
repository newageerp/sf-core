<?php

namespace Newageerp\SfSocket\EventListener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Newageerp\SfSocket\Service\SocketService;
use Psr\Log\LoggerInterface;

class SfSocketListener
{
    protected SocketService $socketService;

    protected LoggerInterface $ajLogger;

    public function __construct(SocketService $socketService, LoggerInterface $ajLogger)
    {
        $this->socketService = $socketService;
        $this->ajLogger = $ajLogger;
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $class = str_replace('\\', '', $entity::class);
        $class = str_replace(['AppEntity', 'Proxies__CG__'], '', $class);

        if ($class === 'Note') {
            if ($entity->getNotify()) {
                foreach ($entity->getNotify() as $notify) {
                    $this->socketService->addToPool(
                        [
                            'room' => 'notes-' . $notify,
                            'action' => 'notes-create',
                            'body' => ['id' => $entity->getId(), 'schema' => 'Note']
                        ]
                    );
                }
            }
        }

        $this->socketService->addToPool(
            [
                'room' => 'all',
                'action' => 'entity-' . $class . '-0',
                'body' => time()
            ]
        );
        $this->socketService->addToPool(
            [
                'room' => 'all',
                'action' => 'data-update-all',
                'body' => [
                    'id' => $entity->getId(),
                    'schema' => $class
                ]

            ]
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // $entity = $args->getObject();
        $class = str_replace('\\', '', $entity::class);
        $class = str_replace(['AppEntity', 'Proxies__CG__'], '', $class);

        $this->socketService->addToPool(
            [
                'room' => 'all',
                'action' => 'entity-' . $class . '-' . $entity->getId(),
                'body' => time()
            ]
        );
        $this->socketService->addToPool(
            [
                'room' => 'all',
                'action' => 'data-update-all',
                'body' => [
                    'id' => $entity->getId(),
                    'schema' => $class
                ]

            ]
        );

        if ($class === 'Note') {
            $this->socketService->addToPool(
                [
                    'room' => 'all',
                    'action' => 'note-' . $entity->getParentId() . '-' . $entity->getParentSchema(),
                    'body' => time()
                ]
            );
        }

    }

}
