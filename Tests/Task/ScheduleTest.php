<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rewieer\TaskSchedulerBundle\Test;

use Rewieer\TaskSchedulerBundle\Task\Schedule;

class ScheduleTest extends \PHPUnit\Framework\TestCase {
  public function testDefault() {
    // By default the cron should just run everyday at midnight
    $schedule = new Schedule();

    $this->assertEquals(
      $schedule->getExpression(),
      "* * * * *"
    );
  }

  public function testHours() {
    $schedule = new Schedule();
    $schedule->hours(12);


    $this->assertEquals(
      $schedule->getExpression(),
      "* 12 * * *"
    );
  }

  public function testMinutes() {
    $schedule = new Schedule();
    $schedule->minutes(12);

    $this->assertEquals(
      $schedule->getExpression(),
      "12 * * * *"
    );
  }

  public function testEveryXMinutes() {
    $schedule = new Schedule();
    $schedule->everyMinutes(5);

    $this->assertEquals(
      $schedule->getExpression(),
      "*/5 * * * *"
    );
  }

  public function testEveryXHours() {
    $schedule = new Schedule();
    $schedule->everyHours(5);

    $this->assertEquals(
      $schedule->getExpression(),
      "* */5 * * *"
    );
  }

  public function testDaily() {
    $schedule = new Schedule("1 2 3 4 5");
    $schedule->daily();

    $this->assertEquals(
      $schedule->getExpression(),
      "1 2 * 4 5"
    );
  }
}