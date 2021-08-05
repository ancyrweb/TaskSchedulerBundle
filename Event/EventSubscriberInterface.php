<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Rewieer\TaskSchedulerBundle\Event;

interface EventSubscriberInterface
{
    /**
     * Return a list of event the user wishes to be notified about
     * Events are :
     * - onStart
     * - beforeTaskRuns
     * - afterTaskRuns
     * - onEnd
     * @return array
     */
    public static function getEvents(): array;
}
