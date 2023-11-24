<?php

namespace Newageerp\SfBins\Service;

use Newageerp\SfControlpanel\Console\LocalConfigUtilsV3;
use Psr\Log\LoggerInterface;

class BinService
{
    protected LoggerInterface $binLogger;

    protected string $storageDir = '';

    public function __construct(LoggerInterface $binLogger)
    {
        $userStorage = LocalConfigUtilsV3::getUserStoragePath() . '/bin';
        if (!is_dir($userStorage)) {
            mkdir($userStorage);
        }

        $this->storageDir = $userStorage;

        $this->binLogger = $binLogger;
    }

    public function execute($group, $package, $arguments = [])
    {
        $binFile = $this->storageDir . '/' . $group . '/' . $package . '/cli-release';
        if (file_exists($binFile)) {
            $timeStart = microtime(true);
            $output = shell_exec($binFile . ' ' . implode(" ", $arguments));
            $timeFinish = microtime(true);

            $this->binLogger->info('Run bin ' . $binFile . ' ' . number_format($timeFinish - $timeStart, 5));

            return $output;
        } else {
            $this->binLogger->info($binFile . ' missing');

            return 'MISSING BIN';
        }
    }
}
