<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Rewieer\TaskSchedulerBundle\Task;

use Rewieer\TaskSchedulerBundle\Event\EventDispatcher;

class Scheduler
{
    private EventDispatcher $dispatcher;

    public function __construct(EventDispatcher $dispatcher = null)
    {
        if ($dispatcher === null) {
            $dispatcher = new EventDispatcher();
        }

        $this->dispatcher = $dispatcher;
    }

    /**
     * @var TaskInterface[]
     */
    private $tasks = [];

    public function addTask(TaskInterface $task): void
    {
        $this->tasks[] = $task;
    }

    public function run($currentTime = 'now'): void
    {
        $this->dispatcher->dispatch(SchedulerEvents::ON_START);

        foreach ($this->tasks as $task) {
            if ($task->isDue($currentTime)) {
                $this->runTask($task);
            } else {
                $this->dispatcher->dispatch(SchedulerEvents::ON_SKIP, [$task]);
            }
        }

        $this->dispatcher->dispatch(SchedulerEvents::ON_END);
    }

    public function runTask(TaskInterface $task): void
    {
        $this->dispatcher->dispatch(SchedulerEvents::BEFORE_TASK_RUNS, [$task]);
        $task->run();
        $this->dispatcher->dispatch(SchedulerEvents::AFTER_TASK_RUNS, [$task]);
    }

    /**
     * @return TaskInterface[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }
}
