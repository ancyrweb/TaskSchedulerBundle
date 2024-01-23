<?php

/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Rewieer\TaskSchedulerBundle\Task;

use Cron\CronExpression;
use Cron\FieldFactory;
use DateTimeInterface;

/**
 * Class Schedule
 * @package Rewieer\TaskSchedulerBundle\Task
 */
class Schedule
{
    private CronExpression $cron;

    /**
     * Schedule constructor.
     * @param string $expr the default cron
     */
    public function __construct(string $expr = '* * * * *')
    {
        $this->cron = new CronExpression($expr, new FieldFactory());
    }

    /**
     * Sets the cron to work at these months
     * @param string|int $months
     */
    public function months($months): self
    {
        $this->cron->setPart(CronExpression::MONTH, $months);
        return $this;
    }

    /**
     * Sets the cron to work at these days of the week
     * ATTENTION: resets daysOfMonth
     * @param string|int $days
     */
    public function daysOfWeek($days): self
    {
        $this->cron->setPart(CronExpression::DAY, '*');
        $this->cron->setPart(CronExpression::WEEKDAY, $days);
        return $this;
    }

    /**
     * Sets the cron to work at these days of the month
     * ATTENTION: resets daysOfWeek
     * @param string|int $days
     */
    public function daysOfMonth($days): self
    {
        $this->cron->setPart(CronExpression::DAY, $days);
        $this->cron->setPart(CronExpression::WEEKDAY, '*');
        return $this;
    }

    /**
     * Sets the cron to work every day
     */
    public function daily(): self
    {
        $this->cron->setPart(CronExpression::DAY, '*');
        return $this;
    }

    /**
     * Set the cron to work at these hours
     * @param string|int $hours
     */
    public function hours($hours): self
    {
        $this->cron->setPart(CronExpression::HOUR, (string)$hours);
        return $this;
    }

    /**
     * Set the cron to work at these minutes
     * @param string|int $minutes
     */
    public function minutes($minutes): self
    {
        $this->cron->setPart(CronExpression::MINUTE, (string)$minutes);
        return $this;
    }

    /**
     * Set the cron to work at every x minutes
     * @param int $minutes
     */
    public function everyMinutes(int $minutes = 1): self
    {
        return $this->everyX($minutes, CronExpression::MINUTE);
    }

    /**
     * Set the cron to work at every x hours
     * @param int $hours
     */
    public function everyHours(int $hours = 1): self
    {
        return $this->everyX($hours, CronExpression::HOUR);
    }

    /**
     * Generic function to update a cron part as an 'everyX' pattern
     * such as 'every 3 hours' or 'every 10 minutes'
     */
    public function everyX(int $time = 1, int $part = CronExpression::MINUTE): self
    {
        if ($time === 0 || $time === 1) {
            $expr = '*';
        } else {
            $expr = '*/' . (string)$time;
        }

        $this->cron->setPart($part, $expr);
        return $this;
    }

    public function getCron(): CronExpression
    {
        return $this->cron;
    }

    public function getExpression(): string
    {
        return $this->cron->getExpression();
    }

    /**
     * Allows setting entire expression in string format like '0 * 2,7,12 * 7'
     * Exposes CronExpressions method directly
     */
    public function setExpression(string $value): self
    {
        $this->cron->setExpression($value);
        return $this;
    }

    public function setPart(int $position, string $value): self
    {
        $this->cron->setPart($position, $value);
        return $this;
    }

    /**
     * Return true if the schedule is due to now
     * @param DateTimeInterface|string $currentTime
     */
    public function isDue($currentTime = 'now'): bool
    {
        return $this->cron->isDue($currentTime);
    }
}
