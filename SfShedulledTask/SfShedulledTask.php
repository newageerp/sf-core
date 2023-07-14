<?php

namespace Newageerp\SfShedulledTask;

use Psr\Log\LoggerInterface;
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;

abstract class SfShedulledTask extends AbstractScheduledTask
{
    protected LoggerInterface $taskLogger;

    public function __construct(LoggerInterface $taskLogger)
    {
        parent::__construct();

        $this->taskLogger = $taskLogger;
    }

    abstract public function runWithTimer(): void;

    public function run(): void
    {
        $timeStart = microtime(true);
        $this->runWithTimer();
        $timeFinish = microtime(true);
        $this->taskLogger->info('Run task ' . $this::class . ' ' . number_format($timeFinish - $timeStart, 5));
    }
}
