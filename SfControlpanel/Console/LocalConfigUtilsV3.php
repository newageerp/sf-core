<?php

namespace Newageerp\SfControlpanel\Console;

class LocalConfigUtilsV3
{
    public static function getConfigCpPath()
    {
        return self::getNaeSfsCpStoragePath();
    }

    public static function getNaeSfsRootPath()
    {
        return isset($_ENV['NAE_SFS_ROOT_PATH']) ? $_ENV['NAE_SFS_ROOT_PATH'] : '/var/www/symfony';
    }

    public static function getNaeSfsCpStoragePath()
    {
        return isset($_ENV['NAE_SFS_CP_STORAGE_PATH']) ? $_ENV['NAE_SFS_CP_STORAGE_PATH'] : '/var/www/symfony/config-storage';
    }
    
    public static function getFrontendGeneratedPath()
    {
        return self::getNaeSfsRootPath() . '/front-generated';
    }
}
