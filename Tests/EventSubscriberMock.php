<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests;

use Rewieer\TaskSchedulerBundle\Event\EventSubscriberInterface;
use Rewieer\TaskSchedulerBundle\Task\SchedulerEvents;

class EventSubscriberMock implements EventSubscriberInterface
{
    public static $stack = [];

    public function onStart(): void
    {
        self::$stack["onStart"] = func_get_args();
    }

    public function beforeTaskRuns(): void
    {
        self::$stack["beforeTaskRuns"] = func_get_args();
    }

    public function afterTaskRuns(): void
    {
        self::$stack["afterTaskRuns"] = func_get_args();
    }

    public function onEnd(): void
    {
        self::$stack["onEnd"] = func_get_args();
    }

    public function onSkip(): void
    {
        self::$stack["onSkip"] = func_get_args();
    }

    public static function getEvents(): array
    {
        return [
            SchedulerEvents::ON_START => "onStart",
            SchedulerEvents::BEFORE_TASK_RUNS => "beforeTaskRuns",
            SchedulerEvents::AFTER_TASK_RUNS => "afterTaskRuns",
            SchedulerEvents::ON_SKIP => "onSkip",
            SchedulerEvents::ON_END => "onEnd",
        ];
    }
}
