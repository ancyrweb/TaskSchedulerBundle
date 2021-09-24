<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rewieer\TaskSchedulerBundle\Task;

use DateTimeInterface;

abstract class AbstractScheduledTask implements TaskInterface
{
    /**
     * @var Schedule
     */
    private $schedule;

    public function __construct()
    {
        $this->schedule = new Schedule();
        $this->initialize($this->schedule);
    }

    /**
     * @param DateTimeInterface|string $currentTime
     * @return bool
     */
    public function isDue($currentTime): bool
    {
        return $this->schedule->isDue($currentTime);
    }

    /**
     * @return Schedule
     */
    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    public function getNextRunDates(int $counter): array
    {
        $result = [];

        if ($counter < 1) {
            return $result;
        }

        for ($i = 0; $i < $counter; $i++) {
            $result[] = $this->schedule->getCron()->getNextRunDate('now', $i)->format(DATE_ATOM);
        }

        return $result;
    }

    abstract protected function initialize(Schedule $schedule);
}
