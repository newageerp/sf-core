<?php

namespace Newageerp\SfConfig\Service;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/app/nae-core/config-cache")
 */
class ConfigService
{
    public function getConfig(string $file)
    {
        $localStorage = LocalConfigUtilsV3::getNaeSfsCpStoragePath();
        $userStorage = LocalConfigUtilsV3::getUserStoragePath();

        $data = [];
        if (file_exists($localStorage . '/' . $file)) {
            $data = json_decode(
                file_get_contents($localStorage . '/' . $file),
                true
            );
        }
        if (file_exists($userStorage . '/' . $file)) {
            $data = array_merge(
                $data,
                json_decode(
                    file_get_contents($userStorage . '/' . $file),
                    true
                )
            );
        }
        return $data;
    }
}
