<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rewieer\TaskSchedulerBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Rewieer\TaskSchedulerBundle\Event\EventDispatcher;

class EventDispatcherTest extends TestCase {
  public function testEventDispatcher() {
    $dispatcher = new EventDispatcher();
    $subscriber = new DummySubscriber();
    $dispatcher->addSubscriber($subscriber);

    $dispatcher->dispatch("foo", [1, 2, 3]);
    $this->assertEquals([1, 2, 3], $subscriber->args);
  }
}