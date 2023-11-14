<?php

namespace Newageerp\SfControlpanel\Console;

class LocalConfigUtils
{
    public static function getCpConfigFile(string $file)
    {
        return self::getNaeSfsCpStoragePath() . '/' . $file . '.json';
    }
    public static function getCpConfigFileData(string $file)
    {
        $file = self::getNaeSfsCpStoragePath() . '/' . $file . '.json';
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
        return json_decode(file_get_contents($file), true);
    }

    // public static function getFrontendHooksPath()
    // {
    //     return self::getNaeSfsRootPath() . '/front-hooks';
    // }

    // public static function getFrontendModelsPath()
    // {
    //     return self::getNaeSfsRootPath() . '/front-models';
    // }

    // public static function getFrontendModelsCachePath()
    // {
    //     return self::getNaeSfsRootPath() . '/front-models-cache';
    // }


    // public static function getFrontendConfigPath()
    // {
    //     return self::getNaeSfsRootPath() . '/front-end-config';
    // }

    public static function getNaeSfsCpStoragePath()
    {
        return isset($_ENV['NAE_SFS_CP_STORAGE_PATH']) ? $_ENV['NAE_SFS_CP_STORAGE_PATH'] : '/var/www/symfony/config-storage';
    }

    public static function getNaeSfsRootPath()
    {
        return isset($_ENV['NAE_SFS_ROOT_PATH']) ? $_ENV['NAE_SFS_ROOT_PATH'] : '/var/www/symfony';
    }

    public static function getFrontendGeneratedPath()
    {
        return self::getNaeSfsRootPath() . '/front-generated';
    }

    public static function getCpDbPath()
    {
        return self::getNaeSfsRootPath() . '/config-storage';
    }

    public static function getPhpVariablesPath()
    {
        return self::getNaeSfsRootPath() . '/src/Config';
    }

    public static function getPhpEntitiesPath()
    {
        return self::getNaeSfsRootPath() . '/src/Entity';
    }

    public static function getPhpControllerPath()
    {
        return self::getNaeSfsRootPath() . '/src/Controller';
    }

    public static function transformCamelCaseToKey(string $key)
    {
        $output = [];

        for ($i = 0; $i < mb_strlen($key); $i++) {
            $l = $key[$i];

            if ($l === mb_strtoupper($l) && !is_numeric($l) && $i !== 0) {
                $output[] = '-';
            }
            $output[] = mb_strtolower($l);
        }

        return implode("", $output);
    }

    public static function transformKeyToCamelCase(string $key)
    {
        $upper = false;
        $output = [];

        for ($i = 0; $i < mb_strlen($key); $i++) {
            $l = $key[$i];

            if ($l === '-') {
                $upper = true;
            } else {
                if ($upper) {
                    $upper = false;
                    $output[] = mb_strtoupper($l);
                } else {
                    $output[] = mb_strtolower($l);
                }
            }
        }

        return implode("", $output);
    }
}
