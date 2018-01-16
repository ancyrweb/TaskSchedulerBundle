<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Task;

use Rewieer\TaskSchedulerBundle\Event\EventDispatcher;

class Scheduler {
  /**
   * @var EventDispatcher
   */
  private $dispatcher;

  public function __construct(EventDispatcher $dispatcher = null) {
    if ($dispatcher === null) {
      $dispatcher = new EventDispatcher();
    }

    $this->dispatcher = $dispatcher;
  }

  /**
   * @var TaskInterface[]
   */
  private $tasks = [];

  /**
   * Adds the task to the task stack
   * @param TaskInterface $task
   */
  public function addTask(TaskInterface $task) {
    $this->tasks[] = $task;
  }

  /**
   * Run due tasks
   * @param string $currentTime
   */
  public function run($currentTime = "now") {
    $this->dispatcher->dispatch(SchedulerEvents::ON_START);
    foreach($this->tasks as $task) {
      if ($task->isDue($currentTime)) {
        $this->runTask($task);
      } else {
        $this->dispatcher->dispatch(SchedulerEvents::ON_SKIP, [$task]);
      }
    }
    $this->dispatcher->dispatch(SchedulerEvents::ON_END);
  }

  /**
   * Run the task
   * @param TaskInterface $task
   */
  public function runTask(TaskInterface $task) {
    $this->dispatcher->dispatch(SchedulerEvents::BEFORE_TASK_RUNS, [$task]);
    $task->run();
    $this->dispatcher->dispatch(SchedulerEvents::AFTER_TASK_RUNS, [$task]);
  }

  /**
   * @return TaskInterface[]
   */
  public function getTasks() {
    return $this->tasks;
  }
}