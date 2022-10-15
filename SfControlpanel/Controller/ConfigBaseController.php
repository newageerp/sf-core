<?php

namespace Newageerp\SfControlpanel\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Newageerp\SfBaseEntity\Controller\OaBaseController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Newageerp\SfSocket\Service\SocketService;

class ConfigBaseController extends OaBaseController
{
    protected string $localStorage;

    protected string $localDbFile;

    public function __construct(EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, SocketService $socketService)
    {
        parent::__construct($em, $eventDispatcher, $socketService);

        $this->localStorage = $_ENV['NAE_SFS_CP_STORAGE_PATH'];
        $this->localDbFile = $_ENV['NAE_SFS_CP_DB_PATH'];
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

    /**
     * @return string
     */
    public function getLocalDbFile(): string
    {
        return $this->localDbFile;
    }

    /**
     * @param string $localDbFile
     */
    public function setLocalDbFile(string $localDbFile): void
    {
        $this->localDbFile = $localDbFile;
    }

}