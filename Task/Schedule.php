<?php

/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Task;

use Cron\CronExpression;
use Cron\FieldFactory;
use DateTimeInterface;

/**
 * Class Schedule
 * @package Rewieer\TaskSchedulerBundle\Task
 */
class Schedule {
  /**
   * @var CronExpression
   */
  private $cron;

  /**
   * Schedule constructor.
   * @param string $expr the default cron
   */
  public function __construct(string $expr = "* * * * *")
  {
    $this->cron = new CronExpression($expr, new FieldFactory());
  }

  /**
   * Sets the cron to work at these months
   * @param string|int $months
   * @return $this
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
   * @return $this
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
   * @return $this
   */
  public function daysOfMonth($days): self
  {
    $this->cron->setPart(CronExpression::DAY, $days);
    $this->cron->setPart(CronExpression::WEEKDAY, '*');
    return $this;
  }

  /**
   * Sets the cron to work every day
   * @return $this
   */
  public function daily(): self
  {
    $this->cron->setPart(CronExpression::DAY, "*");
    return $this;
  }

  /**
   * Set the cron to work at these hours
   * @param string|int $hours
   * @return $this
   */
  public function hours($hours): self
  {
    $this->cron->setPart(CronExpression::HOUR, (string)$hours);
    return $this;
  }

  /**
   * Set the cron to work at these minutes
   * @param string|int $minutes
   * @return $this
   */
  public function minutes($minutes): self
  {
    $this->cron->setPart(CronExpression::MINUTE, (string)$minutes);
    return $this;
  }

  /**
   * Set the cron to work at every x minutes
   * @param int $minutes
   * @return $this
   */
  public function everyMinutes(int $minutes = 1): self
  {
    return $this->everyX($minutes, CronExpression::MINUTE);
  }

  /**
   * Set the cron to work at every x hours
   * @param int $hours
   * @return $this
   */
  public function everyHours(int $hours = 1): self
  {
    return $this->everyX($hours, CronExpression::HOUR);
  }

  /**
   * Generic function to update a cron part as an "everyX" pattern
   * such as "every 3 hours" or "every 10 minutes"
   *
   * @param int $time
   * @param int $part
   * @return $this
   */
  public function everyX(int $time = 1, int $part = CronExpression::MINUTE): self
  {
    if ($time === 0 || $time === 1) {
      $expr = "*";
    } else {
      $expr = "*/" . (string) $time;
    }

    $this->cron->setPart($part, $expr);
    return $this;
  }

  /**
   * @return CronExpression
   */
  public function getCron(): CronExpression
  {
    return $this->cron;
  }

  /**
   * @return string
   */
  public function getExpression(): string
  {
    return $this->cron->getExpression();
  }

    /**
     * Allows setting entire expression in string format like "0 * 2,7,12 * 7"
     * Exposes CronExpressions method directly
     * @param string $value
     * @return $this
     */
  public function setExpression(string $value): self
  {
    $this->cron->setExpression($value);
    return $this;
  }

    /**
     * @param int $position
     * @param string $value
     * @return $this
     */
  public function setPart(int $position, string $value): self
  {
    $this->cron->setPart($position, $value);
    return $this;
  }

  /**
   * Return true if the schedule is due to now
   * @param DateTimeInterface|string $currentTime
   * @return bool
   */
  public function isDue($currentTime = 'now'): bool
  {
    return $this->cron->isDue($currentTime);
  }
}
