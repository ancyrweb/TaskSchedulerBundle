<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rewieer\TaskSchedulerBundle\Test;

use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Event\EventDispatcher;
use Rewieer\TaskSchedulerBundle\Task\Schedule;
use Rewieer\TaskSchedulerBundle\Task\Scheduler;
use Rewieer\TaskSchedulerBundle\Task\TaskInterface;
use Rewieer\TaskSchedulerBundle\Tests\EventSubscriberMock;

class Task implements TaskInterface {
  public static $runCount = 0;
  public $enable = true;

  public function isDue($currentTime) : bool {
    return $this->enable;
  }

  public function run() {
    static::$runCount++;
  }
}

class ScheduledTask extends AbstractScheduledTask {
  public static $runCount = 0;
  protected function initialize(Schedule $schedule) {
   $schedule
     ->daily()
     ->hours(3)
     ->minutes(50);
   }

  public function run() {
    static::$runCount++;
  }
}

class SchedulerTest extends \PHPUnit\Framework\TestCase {
  public function setUp() {
    Task::$runCount = 0;
    ScheduledTask::$runCount = 0;
    EventSubscriberMock::$stack = [];
  }

  public function testRunningTask() {
    $scheduler = new Scheduler();
    $scheduler->addTask(new Task());
    $scheduler->addTask(new ScheduledTask());
    $scheduler->run();

    $this->assertEquals(1, Task::$runCount);
    $this->assertEquals(0, ScheduledTask::$runCount);
  }

  public function testRunningTaskWithDate() {
    $scheduler = new Scheduler();
    $scheduler->addTask(new Task());
    $scheduler->addTask(new ScheduledTask());
    $scheduler->run("2015-06-21 03:50:00");

    $this->assertEquals(1, Task::$runCount);
    $this->assertEquals(1, ScheduledTask::$runCount);
  }

  public function testCallingDispatcher() {
    $eventDispatcher = new EventDispatcher();
    $eventDispatcher->addSubscriber(new EventSubscriberMock());

    $task = new Task();
    $scheduler = new Scheduler($eventDispatcher);
    $scheduler->addTask($task);
    $scheduler->run();

    $this->assertEquals([], EventSubscriberMock::$stack["onStart"]);
    $this->assertEquals([$task], EventSubscriberMock::$stack["beforeTaskRuns"]);
    $this->assertEquals([$task], EventSubscriberMock::$stack["afterTaskRuns"]);
    $this->assertArrayNotHasKey("onSkip", EventSubscriberMock::$stack);
    $this->assertEquals([], EventSubscriberMock::$stack["onEnd"]);
  }

  public function testCallingDispatcherOnSkip() {
    $eventDispatcher = new EventDispatcher();
    $eventDispatcher->addSubscriber(new EventSubscriberMock());

    $task = new Task();
    $task->enable = false;

    $scheduler = new Scheduler($eventDispatcher);
    $scheduler->addTask($task);
    $scheduler->run();

    $this->assertEquals([], EventSubscriberMock::$stack["onStart"]);
    $this->assertArrayNotHasKey("beforeTaskRuns", EventSubscriberMock::$stack);
    $this->assertArrayNotHasKey("afterTaskRuns", EventSubscriberMock::$stack);
    $this->assertEquals([$task], EventSubscriberMock::$stack["onSkip"]);
    $this->assertEquals([], EventSubscriberMock::$stack["onEnd"]);
  }
}