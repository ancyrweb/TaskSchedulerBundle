<?php

namespace Rewieer\TaskSchedulerBundle\Tests\Task;

use Rewieer\TaskSchedulerBundle\Task\Schedule;

class NowScheduledTask extends ScheduledTask
{
    //sleep for 5 seconds to make sure this task matches the schedule
    protected int $sleepCutOff = 55;
    protected function initialize(Schedule $schedule): void
    {
        $dateTime = new \DateTime();
        if($dateTime->format('s') > $this->sleepCutOff){
            sleep(60 - $this->sleepCutOff);
            $dateTime->modify('+1 minute');
        }
        $schedule
            ->hours($dateTime->format('H'))
            ->minutes($dateTime->format('i'));
    }
}
