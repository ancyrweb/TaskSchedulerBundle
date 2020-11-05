<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Tests;

use Rewieer\TaskSchedulerBundle\Task\TaskInterface;

class TaskMock implements TaskInterface {
  static $runCount = 0;
  public $localCount = 0;

  public function isDue($currentTime): bool {
    return true;
  }

  public function getNextRunDates($counter): array {
    return ['nextRunDate', 'anotherRunDate'];
  }

  public function run() {
    self::$runCount++;
    $this->localCount++;
  }
}
