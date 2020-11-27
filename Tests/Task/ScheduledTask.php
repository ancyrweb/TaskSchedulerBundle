<?php

namespace Rewieer\TaskSchedulerBundle\Tests\Task;

use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class ScheduledTask extends AbstractScheduledTask
{
    public static $runCount = 0;

    protected function initialize(Schedule $schedule)
    {
        $schedule
            ->daily()
            ->hours(3)
            ->minutes(50);
    }

    public function run()
    {
        static::$runCount++;
    }
}