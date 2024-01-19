<?php

namespace Rewieer\TaskSchedulerBundle\Tests\Task;

class LongRunningScheduledTask extends NowScheduledTask
{
    public function run(): void
    {
        //force next job to run in next minute
        sleep(61 - (new \DateTime())->format('s'));
        parent::run();
    }
}
