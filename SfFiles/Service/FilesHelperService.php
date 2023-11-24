<?php

namespace Newageerp\SfFiles\Service;

class FilesHelperService
{
    public static function scanDirFix($baseDir)
    {
        $dirs = scandir($baseDir);
        return array_filter(
            $dirs,
            function ($t) {
                return $t !== '.' && $t !== '..';
            }
        );
    }

    public static function scanDirFolders($baseDir)
    {
        $files = self::scanDirFix($baseDir);

        return array_filter(
            $files,
            function ($t) use ($baseDir) {
                return is_dir($baseDir . '/' . $t);
            }
        );
    }

    public static function scanDirFiles($baseDir)
    {
        $files = self::scanDirFix($baseDir);
        return array_filter(
            $files,
            function ($t) use ($baseDir) {
                return is_file($baseDir . '/' . $t);
            }
        );
    }
}
