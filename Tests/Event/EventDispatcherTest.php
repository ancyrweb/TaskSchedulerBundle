<?php
/*
 * (c) Antonny Cyrille <rewieer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Rewieer\TaskSchedulerBundle\Test\Event;

use Rewieer\TaskSchedulerBundle\Event\EventDispatcher;
use Rewieer\TaskSchedulerBundle\Event\EventSubscriberInterface;

class DummySubscriber implements EventSubscriberInterface {
  public $args;
  public function callFoo() {
    $this->args = func_get_args();
  }

  public static function getEvents(): array {
    return [
      "foo" => "callFoo"
    ];
  }
}
class EventDispatcherTest extends \PHPUnit\Framework\TestCase {
  public function testEventDispatcher() {
    $dispatcher = new EventDispatcher();
    $subscriber = new DummySubscriber();
    $dispatcher->addSubscriber($subscriber);

    $dispatcher->dispatch("foo", [1, 2, 3]);
    $this->assertEquals([1, 2, 3], $subscriber->args);
  }
}