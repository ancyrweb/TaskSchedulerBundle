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

class ScheduleTest extends TestCase
{
    public function testDefault(): void
    {
        // should run every day at midnight by default
        $schedule = new Schedule();

        $this->assertEquals(
            $schedule->getExpression(),
            "* * * * *"
        );
    }

    public function testHours(): void
    {
        $schedule = new Schedule();
        $schedule->hours(12);

        $this->assertEquals(
            $schedule->getExpression(),
            "* 12 * * *"
        );
    }

    public function testMinutes(): void
    {
        $schedule = new Schedule();
        $schedule->minutes(12);

        $this->assertEquals(
            $schedule->getExpression(),
            "12 * * * *"
        );
    }

    public function testEveryXMinutes(): void
    {
        $schedule = new Schedule();
        $schedule->everyMinutes(5);

        $this->assertEquals(
            $schedule->getExpression(),
            "*/5 * * * *"
        );
    }

    public function testEveryXHours(): void
    {
        $schedule = new Schedule();
        $schedule->everyHours(5);

        $this->assertEquals(
            $schedule->getExpression(),
            "* */5 * * *"
        );
    }

    public function testDaily(): void
    {
        $schedule = new Schedule("1 2 3 4 5");
        $schedule->daily();

        $this->assertEquals(
            $schedule->getExpression(),
            "1 2 * 4 5"
        );
    }

    public function testMonths(): void
    {
        $schedule = new Schedule("1 2 3 4 5");
        $schedule->months('7-9');

        $this->assertEquals(
            $schedule->getExpression(),
            "1 2 3 7-9 5"
        );
    }

    public function testDaysOfWeek(): void
    {
        $schedule = new Schedule("1 2 3 4 5");
        $schedule->daysOfWeek('mon');

        $this->assertEquals(
            $schedule->getExpression(),
            "1 2 * 4 mon"
        );
    }

    public function testDaysOfMonth(): void
    {
        $schedule = new Schedule("1 2 3 4 5");
        $schedule->daysOfMonth('7,8,9');

        $this->assertEquals(
            $schedule->getExpression(),
            "1 2 7,8,9 4 *"
        );
    }

    public function testSetExpression(): void
    {
        $schedule = new Schedule("* * * * *");
        $schedule->setExpression("0 * * * *");

        $this->assertEquals(
            $schedule->getExpression(),
            "0 * * * *"
        );
    }

    public function testSetExpressionAllowedValues(): void
    {
        $schedule = new Schedule("* * 2,7,12 * *");
        $schedule->setExpression("0 8-12 2,7,12 oct sat,sun");

        $this->assertEquals(
            "0 8-12 2,7,12 oct sat,sun",
            $schedule->getExpression()
        );
    }

    public function testSetPartExpression(): void
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

    public function testInvalidExpressionFullMonth(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $schedule = new Schedule();
        $schedule->setPart(CronExpression::MONTH, 'october');
    }

    public function testInvalidExpressionUnknownValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $schedule = new Schedule();
        $schedule->setPart(CronExpression::MONTH, 'movember');
    }

    public function testInvalidExpressionNegative(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $schedule = new Schedule();
        $schedule->setPart(CronExpression::MINUTE, '-5');
    }
}
