<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Rewieer\TaskSchedulerBundle\Task;

use DateTimeInterface;

interface TaskInterface
{
    /**
     * Return true if the task is due to now
     * @param DateTimeInterface|string $currentTime
     */
    public function isDue($currentTime): bool;

    /**
     * Get the next run dates for this job
     * @return string[]
     */
    public function getNextRunDates(int $counter): array;

    /**
     * Execute the task
     */
    public function run(): void;
}
