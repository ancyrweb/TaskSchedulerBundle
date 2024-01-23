<?php

declare(strict_types=1);

namespace Rewieer\TaskSchedulerBundle\Tests\Task;

use Rewieer\TaskSchedulerBundle\Task\TaskInterface;

class Task implements TaskInterface
{
    public static $runCount = 0;
    public $enable = true;

    public function isDue($currentTime): bool
    {
        return $this->enable;
    }

    public function getNextRunDates($counter): array
    {
        return [];
    }

    public function run(): void
    {
        static::$runCount++;
    }
}
