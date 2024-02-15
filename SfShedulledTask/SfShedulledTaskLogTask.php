<?php

namespace Newageerp\SfShedulledTask;

use Newageerp\SfKeyValue\SfKeyValueService;
use Newageerp\SfShedulledTask\SfShedulledTask;
use Psr\Log\LoggerInterface;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class SfShedulledTaskLogTask extends SfShedulledTask
{
    protected SfKeyValueService $sfKeyValueService;

    public function __construct(
        LoggerInterface $taskLogger,
        SfKeyValueService $sfKeyValueService
    ) {
        parent::__construct($taskLogger);
        $this->sfKeyValueService = $sfKeyValueService;
    }

    protected function initialize(Schedule $schedule)
    {
        $schedule->everyMinutes(1);
    }

    public function runWithTimer(): void
    {
        $this->sfKeyValueService->upsert('SfShedulledTaskLastRun', gmdate('Y-m-d H:i:s'));
    }
}
