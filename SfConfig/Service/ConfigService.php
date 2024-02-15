<?php

namespace Newageerp\SfConfig\Service;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/app/nae-core/config-cache")
 */
class ConfigService
{
    public static function getConfig(string $config, $createOnNotExists = false)
    {
        $file = $config . '.json';

        $localStorage = LocalConfigUtilsV3::getNaeSfsCpStoragePath();
        $userStorage = LocalConfigUtilsV3::getUserStoragePath();

        $data = [];
        if (file_exists($localStorage . '/' . $file)) {
            $data = json_decode(
                file_get_contents($localStorage . '/' . $file),
                true
            );
        } else if ($createOnNotExists) {
            file_put_contents($file, json_encode([]));
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

    public static function getUserConfig(string $config)
    {
        $file = $config . '.json';

        $userStorage = LocalConfigUtilsV3::getUserStoragePath();

        $data = [];

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

    public static function updateUserConfig(string $config, array $data)
    {
        $file = $config . '.json';

        $userStorage = LocalConfigUtilsV3::getUserStoragePath();

        file_put_contents($userStorage.'/'.$file, json_encode($data, JSON_PRETTY_PRINT));
        
    }

    public static function listConfigs()
    {
        $userStorage = LocalConfigUtilsV3::getUserStoragePath();
        $finder = new Finder();

        $finder->files()->in($userStorage);

        $output = [];
        foreach ($finder as $file) {
            $fileNameWithExtension = $file->getRelativePathname();

            $output[] = [
                'name' => $fileNameWithExtension
            ];
        }
        return $output;
    }
}
