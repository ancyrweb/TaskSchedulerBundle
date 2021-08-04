<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rewieer\TaskSchedulerBundle\Tests\Task;

use Cron\CronExpression;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class ScheduleTest extends TestCase {
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

  public function testMonths() {
    $schedule = new Schedule("1 2 3 4 5");
    $schedule->months('7-9');

    $this->assertEquals(
      $schedule->getExpression(),
      "1 2 3 7-9 5"
    );
  }

  public function testDaysOfWeek() {
    $schedule = new Schedule("1 2 3 4 5");
    $schedule->daysOfWeek('mon');

    $this->assertEquals(
      $schedule->getExpression(),
      "1 2 * 4 mon"
    );
  }

  public function testDaysOfMonth() {
    $schedule = new Schedule("1 2 3 4 5");
    $schedule->daysOfMonth('7,8,9');

    $this->assertEquals(
      $schedule->getExpression(),
      "1 2 7,8,9 4 *"
    );
  }

  public function testSetExpression() {
    $schedule = new Schedule("* * * * *");
    $schedule->setExpression("0 * * * *");

    $this->assertEquals(
      $schedule->getExpression(),
      "0 * * * *"
    );
  }

  public function testSetExpressionAllowedValues()
  {
    $schedule = new Schedule("* * 2,7,12 * *");
    $schedule->setExpression("0 8-12 2,7,12 oct sat,sun");

    $this->assertEquals(
      "0 8-12 2,7,12 oct sat,sun",
      $schedule->getExpression()

    );
  }

  public function testSetPartExpression()
  {
    $schedule = new Schedule();
    $schedule->setPart(CronExpression::MINUTE, '0');
    $schedule->setPart(CronExpression::HOUR, '8-12');
    $schedule->setPart(CronExpression::DAY, '2,7,12');
    $schedule->setPart(CronExpression::MONTH, 'oct');
    $schedule->setPart(CronExpression::WEEKDAY, 'sat,sun');

    $this->assertEquals(
      "0 8-12 2,7,12 oct sat,sun",
      $schedule->getExpression()
    );
  }

  public function testInvalidExpressionFullMonth()
  {
    $this->expectException(InvalidArgumentException::class);
    $schedule = new Schedule();
    $schedule->setPart(CronExpression::MONTH, 'october');
  }

  public function testInvalidExpressionUnknownValue()
  {
    $this->expectException(InvalidArgumentException::class);
    $schedule = new Schedule();
    $schedule->setPart(CronExpression::MONTH, 'movember');
  }

  public function testInvalidExpressionNegative()
  {
    $this->expectException(InvalidArgumentException::class);
    $schedule = new Schedule();
    $schedule->setPart(CronExpression::MINUTE, '-5');
  }
}
