<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Task;

interface TaskInterface {
  /**
   * Return true if the task is due to now
   * @param \Datetime|string $currentTime
   * @return bool
   */
  public function isDue($currentTime) : bool;

  /**
   * Get the next run dates for this job
   * @param int $counter
   * @return string[]
   */
  public function getNextRunDates($counter) : array;

  /**
   * Execute the task
   */
  public function run();
}
