<?php

namespace Newageerp\SfControlpanel\Controller;

use Newageerp\SfConfig\Service\ConfigService;
use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(path="/app/nae-core/config-cache")
 */
class ConfigCacheController extends ConfigBaseController
{

    /**
     * @Route(path="/getLocalConfig", methods={"GET"})
     * @OA\Post (operationId="NaeConfigLocalConfigList")
     */
    public function getLocalConfig(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $config = [
            'widgets' => 'widgets.json',
            'builder' => 'builder.json',
            'tabs' => 'tabs.json',
            'defaults' => 'defaults.json',
            'edit' => 'edit.json',
            'view' => 'view.json',
            'settings' => 'settings.json'
        ];

        $output = ['data' => []];

        try {
            foreach ($config as $key => $file) {
                $data = [];
                if (file_exists($this->getLocalStorage() . '/' . $file)) {
                    $data = json_decode(
                        file_get_contents($this->getLocalStorage() . '/' . $file),
                        true
                    );
                }
                if (file_exists(LocalConfigUtilsV3::getUserStoragePath() . '/' . $file)) {
                    $data = array_merge(
                        $data,
                        json_decode(
                            file_get_contents(LocalConfigUtilsV3::getUserStoragePath() . '/' . $file),
                            true
                        )
                    );
                }
                $output['data'][$key] = $data;
            }
            $output['data']['main'] = ConfigService::getConfig('main');
        } catch (\Exception $e) {
            $output['e'] = $e->getMessage();
        }
        return $this->json($output);
    }
}
