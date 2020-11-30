<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Services;

use Psr\Log\LoggerInterface;
use Rewieer\TaskSchedulerBundle\Event\EventSubscriberInterface;
use Rewieer\TaskSchedulerBundle\Task\SchedulerEvents;
use Rewieer\TaskSchedulerBundle\Task\TaskInterface;

/**
 * Class SchedulerLogger
 * @package Rewieer\TaskSchedulerBundle\Services
 * A default logger for the scheduler
 */
class SchedulerLogger implements EventSubscriberInterface {
  private $logger;
  private $start;
  private $current;

  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public function onStart() {
    $this->start = microtime(true);
    $this->logger->info(
      sprintf(
        "[%s] Starting...",
        (new \Datetime())->format("d/m/y H:i:s")
        )
    );
  }

  public function beforeTaskRuns(TaskInterface $task) {
    $this->current = microtime(true);
    $this->logger->info(sprintf(
      "Running %s", get_class($task)
    ));
  }

  public function afterTaskRuns(TaskInterface $task) {
    $time = microtime(true) - $this->current;
    $this->logger->info(sprintf(
      "Finished %s in %fs", get_class($task), $time
    ));
  }

  public function onEnd() {
    $time = microtime(true) - $this->start;
    $this->logger->info(sprintf("Finished ! Took %fs", $time));
  }

  public function onSkip(TaskInterface $task) {
    $this->logger->info(sprintf(
      "Skipped %s", get_class($task)
    ));
  }

  public static function getEvents(): array {
    return [
      SchedulerEvents::ON_START => "onStart",
      SchedulerEvents::BEFORE_TASK_RUNS => "beforeTaskRuns",
      SchedulerEvents::AFTER_TASK_RUNS => "afterTaskRuns",
      SchedulerEvents::ON_SKIP => "onSkip",
      SchedulerEvents::ON_END => "onEnd",
    ];
  }
}