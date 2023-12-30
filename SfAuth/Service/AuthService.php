<?php
namespace Newageerp\SfAuth\Service;

use Newageerp\SfAuth\Interface\IAuthService;
use Newageerp\SfBaseEntity\Object\BaseUser;
use Newageerp\SfConfig\Service\ConfigService;

class AuthService implements IAuthService
{
    private $backendUrl = '';

    private static $_instance = null;

    protected ?BaseUser $user = null;

    private function __construct()
    {
        $config = ConfigService::getConfig('auth');
        if ($config && isset($config['url'])) {
            $this->backendUrl = $config['url'];
        } else {
            $this->backendUrl = 'http://auth:3000';
        }
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof AuthService)) {
            self::$_instance = new AuthService();
        }

        return self::$_instance;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of backendUrl
     */
    public function getBackendUrl()
    {
        return $this->backendUrl;
    }
}