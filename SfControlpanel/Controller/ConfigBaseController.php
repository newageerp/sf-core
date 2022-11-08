<?php

namespace Newageerp\SfControlpanel\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfSocket\Service\SocketService;

class ConfigBaseController extends OaBaseController
{
    protected string $localStorage;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        parent::__construct($em, $eventDispatcher, $socketService);

        $this->localStorage = LocalConfigUtilsV3::getNaeSfsCpStoragePath();
    }

    /**
     * @return string
     */
    public function getLocalStorage(): string
    {
        return $this->localStorage;
    }

    /**
     * @param string $localStorage
     */
    public function setLocalStorage(string $localStorage): void
    {
        $this->localStorage = $localStorage;
    }
}