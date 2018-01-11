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
  public function __construct($expr = "* * * * *") {
    $this->cron = new CronExpression($expr, new FieldFactory());
  }

  /**
   * Sets the cron to work every day
   */
  public function daily() {
    $this->cron->setPart(2, "*");
    return $this;
  }

  /**
   * Set the cron to work at this hour
   * @param int $hour
   * @return $this
   */
  public function hours(int $hour) {
    $this->cron->setPart(1, $hour);
    return $this;
  }

  /**
   * Set the cron to work at this minutes
   * @param int $minutes
   * @return $this
   */
  public function minutes(int $minutes) {
    $this->cron->setPart(0, $minutes);
    return $this;
  }

  /**
   * Set the cron to work at every x minutes
   * @param int $minutes
   * @return Schedule
   */
  public function everyMinutes($minutes = 1) {
    return $this->everyX($minutes, 0);
  }

  /**
   * Set the cron to work at every x hours
   * @param int $hours
   * @return Schedule
   */
  public function everyHours($hours = 1) {
    return $this->everyX($hours, 1);
  }

  /**
   * Generic function to update a cron part as an "everyX" pattern
   * such as "every 3 hours" or "every 10 minutes"
   *
   * @param int $time
   * @param int $part
   * @return $this
   */
  public function everyX($time = 1, int $part) {
    if (!$time || $time === 1) {
      $expr = "*";
    } else {
      $expr = "*/" .intval($time);
    }

    $this->cron->setPart($part, $expr);
    return $this;
  }

  /**
   * @return CronExpression
   */
  public function getCron() {
    return $this->cron;
  }

  /**
   * @return string
   */
  public function getExpression() {
    return $this->cron->getExpression();
  }

  /**
   * Return true if the schedule is due to now
   * @param $currentTime
   * @return bool
   */
  public function isDue($currentTime = 'now') {
    return $this->cron->isDue($currentTime);
  }
}