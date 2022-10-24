<?php

namespace Newageerp\SfUservice\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfSocket\Service\SocketService;

class UControllerBase extends OaBaseController
{
    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        parent::__construct($em, $eventDispatcher, $socketService);
    }
}
