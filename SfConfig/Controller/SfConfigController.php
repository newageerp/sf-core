<?php

namespace Newageerp\SfConfig\Controller;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Newageerp\SfControlpanel\Controller\ConfigBaseController;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/app/nae-core/sf-config")
 */
class SfConfigController extends ConfigBaseController
{

    /**
     * @Route(path="/getConfig")
     */
    public function getConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $conf = $request->get('config');

        return $this->json(['success' => true, 'data' => ConfigService::getConfig($conf)]);
    }

    /**
     * @Route(path="/getUserConfig")
     */
    public function getUserConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $conf = $request->get('config');

        return $this->json(['success' => true, 'data' => ConfigService::getUserConfig($conf)]);
    }

    /**
     * @Route(path="/updateUserConfig")
     */
    public function updateUserConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $conf = $request->get('config');
        $data = $request->get('data');

        ConfigService::updateUserConfig($conf, $data);

        return $this->json(['success' => true]);
    }

    /**
     * @Route(path="/list")
     */
    public function listConfigs()
    {
        return $this->json(['success' => true, 'data' => ConfigService::listConfigs()]);
    }
}
