<?php

namespace Rewieer\TaskSchedulerBundle\Tests\Event;

use Rewieer\TaskSchedulerBundle\Event\EventSubscriberInterface;

class DummySubscriber implements EventSubscriberInterface
{
    public $args;

    public function callFoo(): void
    {
        $this->args = func_get_args();
    }

    public static function getEvents(): array
    {
        return [
            "foo" => "callFoo",
        ];
    }
}
