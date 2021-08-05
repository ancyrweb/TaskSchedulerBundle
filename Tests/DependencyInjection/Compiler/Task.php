<?php

namespace Rewieer\TaskSchedulerBundle\Tests\DependencyInjection\Compiler;

use Rewieer\TaskSchedulerBundle\Task\TaskInterface;

class Task implements TaskInterface
{
    public static $runCount = 0;

    public function isDue($currentTime): bool
    {
        return true;
    }

    public function getNextRunDates($counter): array
    {
        return [];
    }

    public function run(): void
    {
        self::$runCount++;
    }
}
